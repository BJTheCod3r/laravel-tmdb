<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

use Illuminate\Support\Collection;
use IteratorAggregate;
use Traversable;

/**
 * Wraps a TMDB paginated list response ("page", "results", "total_pages",
 * "total_results"), hydrating each result into the given resource class.
 *
 * The wrapper is iterable and array-accessible, so it can be looped over or
 * passed straight back from a controller as JSON.
 *
 * @template TResource of Resource
 *
 * @implements IteratorAggregate<int, TResource>
 */
class Paginated extends Resource implements IteratorAggregate
{
    /** @var Collection<int, TResource> */
    public Collection $results;

    public int $page = 1;

    public int $totalPages = 1;

    public int $totalResults = 0;

    /** @var class-string<TResource> */
    protected string $resourceClass;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  class-string<TResource>  $resourceClass
     */
    public function __construct(array $attributes, string $resourceClass)
    {
        $this->resourceClass = $resourceClass;

        parent::__construct($attributes);
    }

    /**
     * @template T of Resource
     *
     * @param  array<string, mixed>  $attributes
     * @param  class-string<T>  $resourceClass
     * @return self<T>
     */
    public static function of(array $attributes, string $resourceClass): self
    {
        return new self($attributes, $resourceClass);
    }

    protected function hydrate(): void
    {
        $this->page = (int) $this->get('page', 1);
        $this->totalPages = (int) $this->get('total_pages', 1);
        $this->totalResults = (int) $this->get('total_results', 0);
        $this->results = ($this->resourceClass)::collection($this->get('results', []));
    }

    public function hasMorePages(): bool
    {
        return $this->page < $this->totalPages;
    }

    public function nextPage(): ?int
    {
        return $this->hasMorePages() ? $this->page + 1 : null;
    }

    public function isEmpty(): bool
    {
        return $this->results->isEmpty();
    }

    /**
     * @return Traversable<int, TResource>
     */
    public function getIterator(): Traversable
    {
        return $this->results->getIterator();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'results' => $this->results->map(fn (Resource $r) => $r->toArray())->all(),
            'total_pages' => $this->totalPages,
            'total_results' => $this->totalResults,
        ];
    }
}
