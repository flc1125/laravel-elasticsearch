<?php

namespace Flc\Laravel\Elasticsearch\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Elasticsearch 门面
 *
 * @author Flc <i@flc.io>
 */
class Elasticsearch extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'elasticsearch';
    }
}
