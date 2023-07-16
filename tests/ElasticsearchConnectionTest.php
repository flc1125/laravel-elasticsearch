<?php

namespace Flc\Laravel\Elasticsearch\Tests;

use Elasticsearch\Client;
use Flc\Laravel\Elasticsearch\ElasticsearchConnection;
use Flc\Laravel\Elasticsearch\Query\Builder;
use Flc\Laravel\Elasticsearch\Query\Grammar;
use PHPUnit\Framework\TestCase;

class ElasticsearchConnectionTest extends TestCase
{
    protected $connection;

    public function setUp(): void
    {
        parent::setUp();

        $this->connection = new ElasticsearchConnection(
            $this->setupClient(), new Grammar());
    }

    protected function setupClient()
    {
        $client = \Mockery::mock(Client::class);
        $client->allows()->index(['test'])->andReturn([]);
        $client->allows()->search(['test'])->andReturn([]);

        return $client;
    }

    public function testConnection()
    {
        $this->assertInstanceOf(ElasticsearchConnection::class, $this->connection);
    }

    public function testIndex()
    {
        $this->assertInstanceOf(Builder::class, $this->connection->index('test'));
        $this->assertNotInstanceOf(Builder::class, $this->connection->index(['test']));
        $this->assertIsArray($this->connection->index(['test']));
    }

    public function testBuilder()
    {
        $this->assertInstanceOf(Builder::class, $this->connection->builder());
    }

    public function testCall()
    {
        $this->assertIsArray($this->connection->search(['test']));
    }
}
