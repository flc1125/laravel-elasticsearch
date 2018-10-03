<?php

namespace Flc\Laravel\Elasticsearch;

use Elasticsearch\Client as ElasticsearchClient;
use Flc\Laravel\Elasticsearch\Query\Builder;

/**
 * Elasticsearch 连接
 *
 * @author Flc <i@flc.io>
 */
class ElasticsearchConnection
{
    /**
     * @var ElasticsearchClient $client
     */
    protected $client;

    /**
     * 创建一个Elasticsearch 连接
     *
     * @param ElasticsearchClient $client
     */
    public function __construct(ElasticsearchClient $client)
    {
        $this->client = $client;
    }

    /**
     * 指定索引或索引数据
     *
     * @param  string|array $value
     * @return \Flc\Laravel\Elasticsearch\Query\Builder|array
     */
    public function index($value)
    {
        if (is_string($value)) {
            return $this->builder()->index($value);
        }

        return $this->client->index($value);
    }

    /**
     * 调用构建类
     *
     * @return \Flc\Laravel\Elasticsearch\Query\Builder
     */
    public function builder()
    {
        return new Builder(
            $this->client
        );
    }

    /**
     * Dynamically pass methods to the default connection.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->client->$method(...$parameters);
    }
}