<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

use ArrayAccess;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use JsonSerializable;

/**
 * Base class for every typed TMDB resource.
 *
 * Concrete resources declare typed public properties and map them inside
 * {@see hydrate()}, while the raw TMDB payload remains accessible via
 * {@see toArray()}, {@see get()} and array access. This gives callers both a
 * pleasant typed surface and an escape hatch to any field TMDB returns.
 *
 * @implements ArrayAccess<string, mixed>
 */
abstract class Resource implements Arrayable, ArrayAccess, JsonSerializable
{
    /** @var array<string, mixed> The raw TMDB attributes. */
    protected array $attributes;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;

        $this->hydrate();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function make(array $attributes = []): static
    {
        return new static($attributes);
    }

    /**
     * Build a collection of resources from a list of raw attribute arrays.
     *
     * @param  iterable<array<string, mixed>>  $items
     * @return Collection<int, static>
     */
    public static function collection(iterable $items): Collection
    {
        return Collection::make($items)->map(fn (array $item) => static::make($item))->values();
    }

    /**
     * Map the raw attributes onto the resource's typed properties. Subclasses
     * override this; the base implementation does nothing.
     */
    protected function hydrate(): void
    {
        //
    }

    /**
     * Read a raw attribute using "dot" notation, with a default fallback.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return data_get($this->attributes, $key, $default);
    }

    /**
     * Parse a raw date/datetime attribute into a CarbonImmutable instance.
     * Empty strings and nulls (common in TMDB payloads) yield null.
     */
    protected function date(string $key): ?CarbonImmutable
    {
        $value = $this->get($key);

        if (empty($value)) {
            return null;
        }

        return CarbonImmutable::parse($value);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __get(string $name): mixed
    {
        return $this->get($name);
    }

    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    public function offsetExists(mixed $offset): bool
    {
        return data_get($this->attributes, $offset) !== null;
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        data_set($this->attributes, $offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes[$offset]);
    }
}
