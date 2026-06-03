<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Endpoints;

use BjTheCod3r\Tmdb\Exceptions\TmdbException;
use BjTheCod3r\Tmdb\Resources\Keyword;
use BjTheCod3r\Tmdb\Resources\MediaResult;
use BjTheCod3r\Tmdb\Resources\Movie;
use BjTheCod3r\Tmdb\Resources\MovieCollection;
use BjTheCod3r\Tmdb\Resources\Paginated;
use BjTheCod3r\Tmdb\Resources\Person;
use BjTheCod3r\Tmdb\Resources\ProductionCompany;
use BjTheCod3r\Tmdb\Resources\TvShow;

/**
 * The /search endpoints.
 *
 * @see https://developer.themoviedb.org/reference/search-movie
 */
class Search extends Endpoint
{
    /**
     * Search for movies by title.
     *
     * @param  array<string, mixed>  $params  e.g. ['year' => 2010, 'page' => 2].
     * @return Paginated<Movie>
     * @throws TmdbException
     */
    public function movies(string $query, array $params = []): Paginated
    {
        return Paginated::of($this->run('search/movie', $query, $params), Movie::class);
    }

    /**
     * Search for TV series by name.
     *
     * @return Paginated<TvShow>
     * @throws TmdbException
     */
    public function tv(string $query, array $params = []): Paginated
    {
        return Paginated::of($this->run('search/tv', $query, $params), TvShow::class);
    }

    /**
     * Search for people by name.
     *
     * @return Paginated<Person>
     * @throws TmdbException
     */
    public function people(string $query, array $params = []): Paginated
    {
        return Paginated::of($this->run('search/person', $query, $params), Person::class);
    }

    /**
     * Search movies, TV and people in a single request. Each result carries a
     * "media_type"; call {@see MediaResult::asResource()} to get a typed object.
     *
     * @return Paginated<MediaResult>
     * @throws TmdbException
     */
    public function multi(string $query, array $params = []): Paginated
    {
        return Paginated::of($this->run('search/multi', $query, $params), MediaResult::class);
    }

    /**
     * Search for companies by name.
     *
     * @return Paginated<ProductionCompany>
     * @throws TmdbException
     */
    public function companies(string $query, array $params = []): Paginated
    {
        return Paginated::of($this->run('search/company', $query, $params), ProductionCompany::class);
    }

    /**
     * Search for collections by name.
     *
     * @return Paginated<MovieCollection>
     * @throws TmdbException
     */
    public function collections(string $query, array $params = []): Paginated
    {
        return Paginated::of($this->run('search/collection', $query, $params), MovieCollection::class);
    }

    /**
     * Search for keywords by name.
     *
     * @return Paginated<Keyword>
     * @throws TmdbException
     */
    public function keywords(string $query, array $params = []): Paginated
    {
        return Paginated::of($this->run('search/keyword', $query, $params), Keyword::class);
    }

    /**
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     * @throws TmdbException
     */
    protected function run(string $path, string $query, array $params): array
    {
        return $this->client->get($path, array_merge(['query' => $query], $params));
    }
}
