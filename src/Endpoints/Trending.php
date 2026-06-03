<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Endpoints;

use BjTheCod3r\Tmdb\Exceptions\TmdbException;
use BjTheCod3r\Tmdb\Resources\MediaResult;
use BjTheCod3r\Tmdb\Resources\Movie;
use BjTheCod3r\Tmdb\Resources\Paginated;
use BjTheCod3r\Tmdb\Resources\Person;
use BjTheCod3r\Tmdb\Resources\TvShow;

/**
 * The /trending endpoints. The time window is "day" or "week".
 *
 * @see https://developer.themoviedb.org/reference/trending-all
 */
class Trending extends Endpoint
{
    /**
     * Trending across movies, TV and people. Each result carries a
     * "media_type"; call {@see MediaResult::asResource()} for a typed object.
     *
     * @param  'day'|'week'  $window
     * @return Paginated<MediaResult>
     * @throws TmdbException
     */
    public function all(string $window = 'day', array $params = []): Paginated
    {
        return Paginated::of($this->client->get("trending/all/{$window}", $params), MediaResult::class);
    }

    /**
     * @param  'day'|'week'  $window
     * @return Paginated<Movie>
     * @throws TmdbException
     */
    public function movies(string $window = 'day', array $params = []): Paginated
    {
        return Paginated::of($this->client->get("trending/movie/{$window}", $params), Movie::class);
    }

    /**
     * @param  'day'|'week'  $window
     * @return Paginated<TvShow>
     * @throws TmdbException
     */
    public function tv(string $window = 'day', array $params = []): Paginated
    {
        return Paginated::of($this->client->get("trending/tv/{$window}", $params), TvShow::class);
    }

    /**
     * @param  'day'|'week'  $window
     * @return Paginated<Person>
     * @throws TmdbException
     */
    public function people(string $window = 'day', array $params = []): Paginated
    {
        return Paginated::of($this->client->get("trending/person/{$window}", $params), Person::class);
    }
}
