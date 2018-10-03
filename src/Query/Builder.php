<?php

namespace Flc\Laravel\Elasticsearch\Query;

use Elasticsearch\Client as ElasticsearchClient;
use Illuminate\Database\Concerns\BuildsQueries;
use InvalidArgumentException;

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
    protected $type = 'doc';

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
     * @param ElasticsearchClient $client
     */
    public function __construct(ElasticsearchClient $client)
    {
        $this->client = $client;
    }

    /**
     * 指定索引名
     *
     * @param string $value
     *
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
     * @param string $value
     *
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
     * @param array|mixed $columns
     *
     * @return $this
     */
    public function select($columns = ['*'])
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();

        return $this;
    }

    /**
     * 通过文档 ID 查询数据
     *
     * @param mixed $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        $params = [
            'index'   => $this->index,
            'type'    => $this->type,
            'id'      => $id,
            '_source' => $columns,
        ];

        return $this->runExtract(
            $this->client->get($params)
        );
    }

    /**
     * 执行格式化输出数据
     *
     * @param array $result
     * @param array $fields 如为单个，则直接返回数组
     *
     * @return array
     */
    protected function runExtract($result = [], $fields = ['_source'])
    {
        $data = [];

        $fields = is_string($fields) ? explode(',', $fields) : $fields;

        foreach ($fields as $field) {
            if (! isset($result[$field])) {
                throw new InvalidArgumentException("[{$field} not found]");
            }

            $data[$field] = $result[$field];
        }

        if (count($data) == 1) {
            return collect(reset($data));
        }

        return collect($data);
    }

    /*
     * 返回数据
     *
     * @return Collection
     */
    // public function get()
    // {
    //     return collect($this->runSearch());
    // }

    /*
     * 执行搜寻
     *
     * @return array
     */
    // protected function runSearch()
    // {
    //     $params = [
    //         'index' => $this->index,
    //         'type'  => $this->type,
    //         'from'  => 0,
    //         'size'  => 20,
    //         'body'  => [
    //             'query' => [
    //                 'bool' => $this->wheres,
    //             ],
    //             // '_source' => ['*'],
    //             'sort' => $this->orders,
    //         ],
    //     ];

    //     return $this->client->search($params);
    // }
}
