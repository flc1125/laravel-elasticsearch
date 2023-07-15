<?php

namespace Flc\Laravel\Elasticsearch\Tests;

use Flc\Laravel\Elasticsearch\Tests\Traits\HasApplication;

class LaravelTest extends TestCase
{
    use HasApplication;

    public function setUp(): void
    {
        parent::setUp();

        $this->app = $this->createApplication();
    }

    public function testApplication(): void
    {
        $this->assertInstanceOf(\Illuminate\Foundation\Application::class, $this->app);
    }
}
