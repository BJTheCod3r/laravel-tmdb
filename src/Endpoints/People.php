<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Endpoints;

use BjTheCod3r\Tmdb\Exceptions\TmdbException;
use BjTheCod3r\Tmdb\Resources\Image;
use BjTheCod3r\Tmdb\Resources\Paginated;
use BjTheCod3r\Tmdb\Resources\Person;
use Illuminate\Support\Collection;

/**
 * The /person endpoints.
 *
 * @see https://developer.themoviedb.org/reference/person-details
 */
class People extends Endpoint
{
    /**
     * Get the primary information for a person.
     *
     * @param  array<string, mixed>  $params  e.g. ['append_to_response' => ['movie_credits']].
     * @throws TmdbException
     */
    public function details(int $id, array $params = []): Person
    {
        return Person::make($this->client->get("person/{$id}", $this->normalizeAppend($params)));
    }

    /**
     * Get the movie credits for a person.
     *
     * @return array<string, mixed>
     * @throws TmdbException
     */
    public function movieCredits(int $id, array $params = []): array
    {
        return $this->client->get("person/{$id}/movie_credits", $params);
    }

    /**
     * Get the TV credits for a person.
     *
     * @return array<string, mixed>
     * @throws TmdbException
     */
    public function tvCredits(int $id, array $params = []): array
    {
        return $this->client->get("person/{$id}/tv_credits", $params);
    }

    /**
     * Get the combined movie and TV credits for a person.
     *
     * @return array<string, mixed>
     * @throws TmdbException
     */
    public function combinedCredits(int $id, array $params = []): array
    {
        return $this->client->get("person/{$id}/combined_credits", $params);
    }

    /**
     * Get the profile images for a person.
     *
     * @return Collection<int, Image>
     * @throws TmdbException
     */
    public function images(int $id): Collection
    {
        return Image::collection($this->client->get("person/{$id}/images")['profiles'] ?? []);
    }

    /**
     * @return array<string, mixed>
     * @throws TmdbException
     */
    public function externalIds(int $id): array
    {
        return $this->client->get("person/{$id}/external_ids");
    }

    /**
     * @throws TmdbException
     */
    public function latest(): Person
    {
        return Person::make($this->client->get('person/latest'));
    }

    /**
     * @return Paginated<Person>
     * @throws TmdbException
     */
    public function popular(array $params = []): Paginated
    {
        return Paginated::of($this->client->get('person/popular', $params), Person::class);
    }
}
