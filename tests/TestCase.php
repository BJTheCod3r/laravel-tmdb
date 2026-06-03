<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Tests;

use BjTheCod3r\Tmdb\TmdbServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            TmdbServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('tmdb.token', 'test-read-access-token');
        $app['config']->set('tmdb.api_key', null);
        $app['config']->set('tmdb.language', 'en-US');
        $app['config']->set('tmdb.region', null);
        $app['config']->set('tmdb.retry.times', 0);
    }
}
