<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb;

use BjTheCod3r\Tmdb\Client\TmdbClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class TmdbServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/tmdb.php', 'tmdb');

        $this->app->singleton(TmdbClient::class, function (Application $app) {
            return new TmdbClient($app['config']->get('tmdb', []));
        });

        $this->app->singleton(Tmdb::class, function (Application $app) {
            return new Tmdb($app->make(TmdbClient::class));
        });

        // Allow resolving the manager via the "tmdb" container alias.
        $this->app->alias(Tmdb::class, 'tmdb');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/tmdb.php' => $this->app->configPath('tmdb.php'),
            ], 'tmdb-config');
        }
    }
}
