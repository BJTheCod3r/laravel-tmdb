<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Endpoints;

use BjTheCod3r\Tmdb\Exceptions\TmdbException;

/**
 * The /configuration endpoints. Useful for building image URLs and resolving
 * the list of supported countries, languages, jobs and timezones.
 *
 * @see https://developer.themoviedb.org/reference/configuration-details
 */
class Configuration extends Endpoint
{
    /**
     * Get the system-wide configuration, including image base URLs and the
     * available poster/backdrop/profile sizes.
     *
     * @return array<string, mixed>
     * @throws TmdbException
     */
    public function details(): array
    {
        return $this->client->get('configuration');
    }

    /**
     * @return array<int, array<string, mixed>>
     * @throws TmdbException
     */
    public function countries(): array
    {
        return $this->client->get('configuration/countries');
    }

    /**
     * @return array<int, array<string, mixed>>
     * @throws TmdbException
     */
    public function languages(): array
    {
        return $this->client->get('configuration/languages');
    }

    /**
     * @return array<int, array<string, mixed>>
     * @throws TmdbException
     */
    public function jobs(): array
    {
        return $this->client->get('configuration/jobs');
    }

    /**
     * @return array<int, array<string, mixed>>
     * @throws TmdbException
     */
    public function timezones(): array
    {
        return $this->client->get('configuration/timezones');
    }
}
