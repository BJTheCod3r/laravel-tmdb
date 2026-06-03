<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Endpoints;

use BjTheCod3r\Tmdb\Client\TmdbClient;
use BjTheCod3r\Tmdb\Exceptions\TmdbException;
use BjTheCod3r\Tmdb\Resources\Paginated;
use BjTheCod3r\Tmdb\Resources\Resource;

/**
 * A fluent query builder for the TMDB /discover endpoints. Filters are
 * accumulated and sent when {@see get()} is called.
 *
 * @template TResource of Resource
 */
class DiscoverBuilder
{
    /** @var array<string, mixed> */
    protected array $filters = [];

    /**
     * @param  'movie'|'tv'  $mediaType
     * @param  class-string<TResource>  $resourceClass
     */
    public function __construct(
        protected TmdbClient $client,
        protected string $mediaType,
        protected string $resourceClass,
    ) {
    }

    /**
     * Set an arbitrary discover filter (e.g. "with_genres", "primary_release_year").
     */
    public function where(string $key, mixed $value): static
    {
        $this->filters[$key] = $value;

        return $this;
    }

    /**
     * Merge a batch of filters at once.
     *
     * @param  array<string, mixed>  $filters
     */
    public function filters(array $filters): static
    {
        $this->filters = array_merge($this->filters, $filters);

        return $this;
    }

    public function sortBy(string $field): static
    {
        return $this->where('sort_by', $field);
    }

    public function page(int $page): static
    {
        return $this->where('page', $page);
    }

    public function year(int $year): static
    {
        $key = $this->mediaType === 'tv' ? 'first_air_date_year' : 'primary_release_year';

        return $this->where($key, $year);
    }

    /**
     * @param  array<int, int>|string  $genreIds  Comma-joined or array of genre IDs.
     */
    public function withGenres(array|string $genreIds): static
    {
        return $this->where('with_genres', is_array($genreIds) ? implode(',', $genreIds) : $genreIds);
    }

    public function withMinimumVoteAverage(float $value): static
    {
        return $this->where('vote_average.gte', $value);
    }

    /**
     * Execute the discover request.
     *
     * @return Paginated<TResource>
     * @throws TmdbException
     */
    public function get(): Paginated
    {
        return Paginated::of(
            $this->client->get("discover/{$this->mediaType}", $this->filters),
            $this->resourceClass,
        );
    }
}
