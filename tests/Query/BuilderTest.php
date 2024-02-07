<?php

namespace Flc\Laravel\Elasticsearch\Tests\Query;

use Flc\Laravel\Elasticsearch\Query\Builder;
use Flc\Laravel\Elasticsearch\Query\Grammar;
use Flc\Laravel\Elasticsearch\Tests\Traits\WithElasticsearchClient;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    use WithElasticsearchClient;

    protected $grammar;

    public function setUp(): void
    {
        parent::setUp();

        $this->grammar = new Grammar();
        $this->client  = $this->withElasticsearchClient();
    }

    protected function newBuilder(): Builder
    {
        return new Builder($this->client, $this->grammar);
    }

    public function testIndex()
    {
        $this->assertSame([
            'index'            => 'test',
            'track_total_hits' => true,
        ], $this->newBuilder()->index('test')->toSearch());

        try {
            $this->newBuilder()->toSearch();
        } catch (\Exception $e) {
            $this->assertInstanceOf(\InvalidArgumentException::class, $e);
        }
    }

    public function testType()
    {
        $this->assertSame([
            'index'            => 'test',
            'type'             => 'test',
            'track_total_hits' => true,
        ], $this->newBuilder()
            ->index('test')
            ->type('test')->toSearch());
    }
}
