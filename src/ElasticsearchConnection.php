<?php

namespace Flc\Laravel\Elasticsearch;

use Elasticsearch\Client as ElasticsearchClient;
use Flc\Laravel\Elasticsearch\Query\Builder;
use Flc\Laravel\Elasticsearch\Query\Grammar;
use Flc\Laravel\Elasticsearch\Query\Processor;

/**
 * Elasticsearch 连接
 *
 * @author Flc <i@flc.io>
 */
class ElasticsearchConnection
{
    /**
     * @var ElasticsearchClient
     */
    protected $client;

    /**
     * @var Grammar
     */
    protected $grammar;

    /**
     * @var Processor
     */
    protected $processor;

    /**
     * 创建一个Elasticsearch 连接
     *
     * @param ElasticsearchClient $client
     * @param Grammar             $grammar
     * @param Processor           $processor
     */
    public function __construct(ElasticsearchClient $client, Grammar $grammar, Processor $processor)
    {
        $this->client    = $client;
        $this->grammar   = $grammar;
        $this->processor = $processor;
    }

    /**
     * 指定索引或索引数据
     *
     * @param string|array $value
     *
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
            $this->client, $this->grammar, $this->processor
        );
    }

    /**
     * Dynamically pass methods to the default connection.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->client->$method(...$parameters);
    }
}
