<?php

namespace Flc\Laravel\Elasticsearch;

use Elasticsearch\ClientBuilder;
use Flc\Laravel\Elasticsearch\Query\Grammar;

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
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * 获取一个单例连接
     *
     * @param string|null $name
     *
     * @return ElasticsearchConnection
     */
    public function connection($name = null): ElasticsearchConnection
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
    protected function getDefaultConnection(): string
    {
        return $this->app['config']['elasticsearch.default'];
    }

    /**
     * 通过别名生成连接实例
     *
     * @param string $name
     *
     * @return ElasticsearchConnection
     */
    protected function resolve(string $name): ElasticsearchConnection
    {
        $config = $this->getConfig($name);

        return $this->makeConnection($config);
    }

    /**
     * 获取配置
     *
     * @param string $name 配置别名
     *
     * @return array
     */
    protected function getConfig(string $name): array
    {
        $connections = $this->app['config']['elasticsearch.connections'];

        if (! isset($connections[$name])) {
            throw new \InvalidArgumentException("Elasticsearch [{$name}] not configured.");
        }

        return $connections[$name];
    }

    /**
     * 通过配置返回客户端连接实例
     *
     * @param array $config
     *
     * @return ElasticsearchConnection
     */
    protected function makeConnection(array $config): ElasticsearchConnection
    {
        return new ElasticsearchConnection(
            ClientBuilder::create()
                ->setHosts($config['host'])
                ->build(),
            new Grammar()
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
    public function __call(string $method, array $parameters)
    {
        return $this->connection()->$method(...$parameters);
    }
}
