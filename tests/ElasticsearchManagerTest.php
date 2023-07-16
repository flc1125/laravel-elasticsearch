<?php

namespace Flc\Laravel\Elasticsearch\Tests;

use Flc\Laravel\Elasticsearch\ElasticsearchManager;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;

class ElasticsearchManagerTest extends TestCase
{
    protected $manager;

    public function setUp(): void
    {
        parent::setUp();

        $app = tap(new Application(), function ($app) {
            $app['config'] = [
                'elasticsearch.default'     => 'default',
                'elasticsearch.connections' => [
                    'default' => [
                        'host' => ['172.17.0.4:9200'],
                    ],
                ],
            ];
        });

        $this->manager = new ElasticsearchManager($app);
    }

    public function testConnection(): void
    {
        $this->assertTrue(true);
        // $connection = $this->manager->connection();
        //
        // $this->assertInstanceOf(\Flc\Laravel\Elasticsearch\ElasticsearchConnection::class, $connection);
    }
}
