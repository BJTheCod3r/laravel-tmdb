<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Endpoints;

use BjTheCod3r\Tmdb\Client\TmdbClient;
use Illuminate\Support\Arr;

/**
 * Base class for every TMDB endpoint group. Holds the shared client and a
 * couple of small helpers used when shaping query parameters.
 */
abstract class Endpoint
{
    public function __construct(protected TmdbClient $client)
    {
    }

    /**
     * Normalise an "append_to_response" option: TMDB expects a comma-joined
     * string, but we accept either a string or an array for convenience.
     *
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    protected function normalizeAppend(array $params): array
    {
        if (is_array($params['append_to_response'] ?? null)) {
            $params['append_to_response'] = Arr::join($params['append_to_response'], ',');
        }

        return $params;
    }
}
