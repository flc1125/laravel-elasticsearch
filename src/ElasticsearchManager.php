<?php

namespace Flc\Laravel\Elasticsearch;

use InvalidArgumentException;

/**
 * Elasticsearch Manager
 *
 * @author Flc <i@flc.io>
 */
class ElasticsearchManager
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * 已连接的实例
     *
     * @var array
     */
    protected $connections = [];

    /**
     * 创建新的 Elasticsearch 实例
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function connection($name = null)
    {
        $name = $name ?? $this->getDefaultConnection();

        if (! isset($this->connections[$name])) {
            $this->connections[$name] = $this->resolve($name);
        }

        return $this->connections[$name];
    }

    /**
     * 返回默认的连接名
     *
     * @return string
     */
    protected function getDefaultConnection()
    {
        return $this->app['config']['elasticsearch.default'];
    }

    /**
     * 通过别名生成连接实例
     *
     * @param  string $name
     * @return ElastisearchClient
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        return $this->getElasticsearchClient($config);
    }

    /**
     * 获取配置
     *
     * @param  string $name 配置别名
     * @return array
     */
    protected function getConfig($name)
    {
        $connections = $this->app['config']['elasticsearch.connections'];

        if (! isset($connections[$name])) {
            throw new InvalidArgumentException("Elasticsearch [{$name}] not configured.");
        }

        return $connections[$name];
    }

    /**
     * 通过配置返回 Elasticsearch 客户端实例
     *
     * @param  array $config
     * @return ElasticsearchClient
     */
    protected function getElasticsearchClient($config)
    {
        return (new ElasticsearchClient($config['host']))->build();
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
        return $this->connection()->$method(...$parameters);
    }
}