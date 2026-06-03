<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Endpoints;

use BjTheCod3r\Tmdb\Exceptions\TmdbException;
use BjTheCod3r\Tmdb\Resources\Genre;
use Illuminate\Support\Collection;

/**
 * The /genre endpoints.
 *
 * @see https://developer.themoviedb.org/reference/genre-movie-list
 */
class Genres extends Endpoint
{
    /**
     * Get the list of official movie genres.
     *
     * @return Collection<int, Genre>
     * @throws TmdbException
     */
    public function movies(array $params = []): Collection
    {
        return Genre::collection($this->client->get('genre/movie/list', $params)['genres'] ?? []);
    }

    /**
     * Get the list of official TV genres.
     *
     * @return Collection<int, Genre>
     * @throws TmdbException
     */
    public function tv(array $params = []): Collection
    {
        return Genre::collection($this->client->get('genre/tv/list', $params)['genres'] ?? []);
    }
}
