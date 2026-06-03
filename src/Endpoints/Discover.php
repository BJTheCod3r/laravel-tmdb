<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Endpoints;

use BjTheCod3r\Tmdb\Resources\Movie;
use BjTheCod3r\Tmdb\Resources\TvShow;

/**
 * Entry point to the /discover endpoints. Returns a {@see DiscoverBuilder} so
 * filters can be chained fluently.
 *
 * @see https://developer.themoviedb.org/reference/discover-movie
 */
class Discover extends Endpoint
{
    /**
     * @return DiscoverBuilder<Movie>
     */
    public function movies(): DiscoverBuilder
    {
        return new DiscoverBuilder($this->client, 'movie', Movie::class);
    }

    /**
     * @return DiscoverBuilder<TvShow>
     */
    public function tv(): DiscoverBuilder
    {
        return new DiscoverBuilder($this->client, 'tv', TvShow::class);
    }
}
