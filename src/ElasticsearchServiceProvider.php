<?php 

namespace Flc\Laravel\Elasticsearch;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * Elasticsearch 服务者
 *
 * @author Flc <i@flc.io>
 */
class ElasticsearchServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath($raw = __DIR__ . '/../config/elasticsearch.php') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('elasticsearch.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('elasticsearch');
        }

        $this->mergeConfigFrom($source, 'elasticsearch');
    }

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