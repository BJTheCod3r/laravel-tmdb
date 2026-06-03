<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Facades;

use BjTheCod3r\Tmdb\Endpoints\Configuration;
use BjTheCod3r\Tmdb\Endpoints\Discover;
use BjTheCod3r\Tmdb\Endpoints\Genres;
use BjTheCod3r\Tmdb\Endpoints\Movies;
use BjTheCod3r\Tmdb\Endpoints\People;
use BjTheCod3r\Tmdb\Endpoints\Search;
use BjTheCod3r\Tmdb\Endpoints\Trending;
use BjTheCod3r\Tmdb\Endpoints\Tv;
use BjTheCod3r\Tmdb\Support\ImageUrl;
use BjTheCod3r\Tmdb\Tmdb as TmdbManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Movies movies()
 * @method static Tv tv()
 * @method static People people()
 * @method static Search search()
 * @method static Discover discover()
 * @method static Trending trending()
 * @method static Genres genres()
 * @method static Configuration configuration()
 * @method static ImageUrl image()
 * @method static \BjTheCod3r\Tmdb\Client\TmdbClient client()
 *
 * @see \BjTheCod3r\Tmdb\Tmdb
 */
class Tmdb extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TmdbManager::class;
    }
}
