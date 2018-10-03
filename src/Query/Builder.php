<?php

namespace Flc\Laravel\Elasticsearch\Query;

use Elasticsearch\Client as ElasticsearchClient;

/**
 * Elasticsearch 查询构建类
 *
 * @author Flc <i@flc.io>
 */
class Builder
{
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
    protected $type = 'docs';

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







    public function info()
    {
        return $this->client->info();
    }
}