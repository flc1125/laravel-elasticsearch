<?php

namespace Flc\Laravel\Elasticsearch\Tests;

use Elasticsearch\Client;

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
