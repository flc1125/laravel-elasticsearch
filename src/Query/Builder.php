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
     * 搜寻条件
     *
     * @var array
     */
    protected $wheres = [
        'must'     => [],
        'must_not' => [],
    ];

    /**
     * 排序
     *
     * @var array
     */
    protected $sorts = [];

    /**
     * 从X条开始查询
     *
     * @var int
     */
    protected $from;

    /**
     * 获取数量
     *
     * @var int
     */
    protected $size;

    // protected $aggs = [];

    /**
     * 需要查询的字段
     *
     * @var array
     */
    // protected $columns;

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
     * 按自定字段排序
     *
     * @param string $column
     * @param string $direction
     *
     * @return $this
     */
    public function orderBy($column, $direction = 'asc')
    {
        $this->sorts[] = [
            $column => [
                'order' => strtolower($direction) == 'asc' ? 'asc' : 'desc',
            ],
        ];

        return $this;
    }

    /**
     * offset 方法别名
     *
     * @param int $value
     *
     * @return $this
     */
    public function skip($value)
    {
        return $this->offset($value);
    }

    /**
     * 跳过X条数据
     *
     * @param int $value
     *
     * @return $this
     */
    public function offset($value)
    {
        if ($value >= 0) {
            $this->from = $value;
        }

        return $this;
    }

    /**
     * limit 方法别名
     *
     * @param int $value
     *
     * @return $this
     */
    public function take($value)
    {
        return $this->limit($value);
    }

    /**
     * 设置获取的数据量
     *
     * @param int $value
     *
     * @return $this
     */
    public function limit($value)
    {
        if ($value >= 0) {
            $this->size = $value;
        }

        return $this;
    }

    /**
     * 以分页形式获取指定数量数据
     *
     * @param int $page
     * @param int $perPage
     *
     * @return $this
     */
    public function forPage($page, $perPage = 15)
    {
        return $this->skip(($page - 1) * $perPage)->take($perPage);
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
    public function get()
    {
        return collect($this->runSearch());
    }

    /*
     * 执行搜寻
     *
     * @return array
     */
    protected function runSearch()
    {
        $params = [
            'index' => $this->index,
            'type'  => $this->type,
            'from'  => 0,
            'size'  => 20,
            'body'  => [
                'query' => [
                    'bool' => $this->wheres,
                ],
                // '_source' => ['*'],
                // 'sort' => $this->orders,
            ],
        ];

        return $this->client->search(
            $this->toQuery()
        );
    }

    /**
     * 返回请求参数
     *
     * @return array
     */
    public function toQuery()
    {
        $query = [
            'index' => $this->index,
            'body'  => [],
            'from'  => $this->from,
            'size'  => $this->size,
        ];
    }
}
