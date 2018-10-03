<?php

namespace Flc\Laravel\Elasticsearch\Query;

use Elasticsearch\Client as ElasticsearchClient;
use Illuminate\Database\Concerns\BuildsQueries;

/**
 * Elasticsearch 查询构建类
 *
 * @author Flc <i@flc.io>
 */
class Builder
{
    use BuildsQueries;

    /**
     * Elasticsearch Client
     *
     * @var \Elasticsearch\Client
     */
    protected $client;

    /**
     * 索引名
     *
     * @var string
     */
    protected $index;

    /**
     * 索引Type
     *
     * @var string
     */
    protected $type;

    /**
     * 需要查询的字段
     *
     * @var array
     */
    protected $columns;

    /**
     * 搜寻条件
     *
     * @var array
     */
    public $wheres = [
        'must'     => [],
        'must_not' => [],
    ];

    /**
     * 实例化一个构建链接
     *
     * @param ElasticsearchClient $client [description]
     */
    function __construct(ElasticsearchClient $client)
    {
        $this->client = $client;
    }

    /**
     * 指定索引名
     *
     * @param  string $value
     * @return $this
     */
    public function index($value)
    {
        $this->index = $value;

        return $this;
    }

    /**
     * 指定type
     *
     * @param  string $value
     * @return $this
     */
    public function type($value)
    {
        $this->type = $value;

        return $this;
    }

    /**
     * 指定需要查询获取的字段
     *
     * @param  array|mixed  $columns
     * @return $this
     */
    public function select($columns = ['*'])
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();

        return $this;
    }

    /**
     * 返回数据
     *
     * @return Collection
     */
    public function get()
    {
        return collect($this->processSelect());
    }

    /**
     * 处理搜寻结果
     *
     * @return array
     */
    protected function processSelect()
    {
        $result = $this->runSelect();

        // 格式化数据...

        return $result;
    }

    /**
     * 执行搜寻
     *
     * @return array
     */
    protected function runSelect()
    {
        return $this->client->search(
            $this->toParams()
        );
    }

    /**
     * 执行搜索的参数
     *
     * @return array
     */
    protected function toParams()
    {
        $params = [
            'index' => $this->index,
            'from'  => 0,
            'size'  => 20,
            'body'  => [
                'query'   => [
                    'bool' => $this->wheres,
                ],
                // '_source' => ['*'],
                'sort'    => $this->orders,
            ],
        ];

        if ($this->type) {
            $params['type'] = $this->type;
        }
    }







    public function info()
    {
        return $this->client->info();
    }
}