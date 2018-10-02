<?php 

namespace Flc\Laravel\Elasticsearch;

use Elasticsearch\ClientBuilder;

/**
 * Elasticsearch 官方客户端
 *
 * @author Flc <i@flc.io>
 * @link   http://flc.io
 * @see    https://www.elastic.co/guide/en/elasticsearch/client/php-api/6.0/index.html
 */
class ElasticsearchClient
{
    /**
     * config
     *
     * @var mixed
     */
    protected $host;
    
    /**
     * 创建一个客户端连接
     *
     * @param mixed $host
     */
    public function __construct($host)
    {
        $this->host = $host;
    }
    
    /**
     * 创建客户端
     *
     * @return \Elasticsearch\Client
     */
    public function build()
    {
        return ClientBuilder::create()
            ->setHosts($this->host)
            ->build();
    }
}