<?php

namespace Flc\Laravel\Elasticsearch\Tests\Other;

use Elasticsearch\Client;
use Flc\Laravel\Elasticsearch\Tests\TestCase;

class ElasticsearchClientTest extends TestCase
{
    public function testClientVersion(): void
    {
        $version = Client::VERSION;

        $this->assertIsString($version);
        $this->assertTrue(version_compare($version, '6.0.0', '>='));
        $this->assertTrue(version_compare($version, '8.0.0', '<'));
    }
}
