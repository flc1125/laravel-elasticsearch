<?php 

namespace Flc\Laravel\Elasticsearch;

use Elasticsearch\ClientBuilder;

/**
* 123
*/
class ElasticsearchClient
{
    /**
     * config
     *
     * @var array
     */
    protected $config = [];
    
    /**
     * 创建一个客户端连接
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
    }
    
    /**
     * 创建客户端
     *
     * @return \Elasticsearch\Client
     */
    public function build()
    {
        return ClientBuilder::create($this->config)->build();
    }
}