<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb;

use BjTheCod3r\Tmdb\Client\TmdbClient;
use BjTheCod3r\Tmdb\Endpoints\Configuration;
use BjTheCod3r\Tmdb\Endpoints\Discover;
use BjTheCod3r\Tmdb\Endpoints\Genres;
use BjTheCod3r\Tmdb\Endpoints\Movies;
use BjTheCod3r\Tmdb\Endpoints\People;
use BjTheCod3r\Tmdb\Endpoints\Search;
use BjTheCod3r\Tmdb\Endpoints\Trending;
use BjTheCod3r\Tmdb\Endpoints\Tv;
use BjTheCod3r\Tmdb\Support\ImageUrl;

/**
 * The main entry point for the package. Exposes each TMDB endpoint group as a
 * memoised accessor so the facade can read fluently:
 *
 *     Tmdb::movies()->details(27205);
 *     Tmdb::search()->multi('matrix');
 *     Tmdb::trending()->movies('week');
 */
class Tmdb
{
    /** @var array<string, object> */
    protected array $endpoints = [];

    protected ?ImageUrl $imageUrl = null;

    public function __construct(protected TmdbClient $client)
    {
    }

    public function movies(): Movies
    {
        return $this->endpoint(Movies::class);
    }

    public function tv(): Tv
    {
        return $this->endpoint(Tv::class);
    }

    public function people(): People
    {
        return $this->endpoint(People::class);
    }

    public function search(): Search
    {
        return $this->endpoint(Search::class);
    }

    public function discover(): Discover
    {
        return $this->endpoint(Discover::class);
    }

    public function trending(): Trending
    {
        return $this->endpoint(Trending::class);
    }

    public function genres(): Genres
    {
        return $this->endpoint(Genres::class);
    }

    public function configuration(): Configuration
    {
        return $this->endpoint(Configuration::class);
    }

    /**
     * Build absolute TMDB image URLs from the relative paths returned on
     * resources (e.g. $movie->posterPath).
     */
    public function image(): ImageUrl
    {
        return $this->imageUrl ??= new ImageUrl;
    }

    /**
     * Escape hatch: the underlying HTTP client, for endpoints not yet wrapped.
     */
    public function client(): TmdbClient
    {
        return $this->client;
    }

    /**
     * Resolve (and memoise) an endpoint group instance.
     *
     * @template T of object
     *
     * @param  class-string<T>  $class
     * @return T
     */
    protected function endpoint(string $class): object
    {
        return $this->endpoints[$class] ??= new $class($this->client);
    }
}
