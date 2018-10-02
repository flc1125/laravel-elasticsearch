<?php 

namespace Flc\Laravel\Elasticsearch;

use Illuminate\Support\ServiceProvider;

/**
 * Elasticsearch 服务者
 *
 * @author Flc <i@flc.io>
 */
class ElasticsearchServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('elasticsearch', function ($app) {
            return new ElasticsearchManager($app);
        });
    }
}