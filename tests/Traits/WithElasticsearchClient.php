<?php

namespace Flc\Laravel\Elasticsearch\Tests\Traits;

use Elasticsearch\Client;

trait WithElasticsearchClient
{
    protected function withElasticsearchClient()
    {
        $client = \Mockery::mock(Client::class);
        $client->allows()->index(['test'])->andReturn([]);
        $client->allows()->search(['test'])->andReturn([]);

        return $client;
    }
}
