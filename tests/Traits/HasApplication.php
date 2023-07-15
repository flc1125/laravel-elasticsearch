<?php

namespace Flc\Laravel\Elasticsearch\Tests\Traits;

use Illuminate\Foundation\Application;

trait HasApplication
{
    protected $app;

    /**
     * @return Application
     */
    protected function createApplication(): Application
    {
        return new Application();
    }
}
