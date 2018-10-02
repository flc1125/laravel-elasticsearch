<?php

namespace Flc\Laravel\Elasticsearch;

use Elasticsearch\Client as ElasticsearchRawClient;

/**
 * Elasticsearch 连接
 *
 * @author Flc <i@flc.io>
 */
class ElasticsearchConnection
{
    /**
     * @var ElasticsearchRawClient $client
     */
    protected $client;

    /**
     * 创建一个Elasticsearch 连接
     *
     * @param ElasticsearchRawClient $client
     */
    public function __construct(ElasticsearchRawClient $client)
    {
        $this->client = $client;
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