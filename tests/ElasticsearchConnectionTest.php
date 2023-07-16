<?php

namespace Flc\Laravel\Elasticsearch\Tests;

use Flc\Laravel\Elasticsearch\ElasticsearchConnection;
use Flc\Laravel\Elasticsearch\Query\Builder;
use Flc\Laravel\Elasticsearch\Query\Grammar;
use Flc\Laravel\Elasticsearch\Tests\Traits\WithElasticsearchClient;
use PHPUnit\Framework\TestCase;

class ElasticsearchConnectionTest extends TestCase
{
    use WithElasticsearchClient;

    protected $connection;

    public function setUp(): void
    {
        parent::setUp();

        $this->connection = new ElasticsearchConnection(
            $this->withElasticsearchClient(), new Grammar());
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
