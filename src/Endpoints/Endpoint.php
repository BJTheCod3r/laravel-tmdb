<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Endpoints;

use BjTheCod3r\Tmdb\Client\TmdbClient;

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
        if (isset($params['append_to_response']) && is_array($params['append_to_response'])) {
            $params['append_to_response'] = implode(',', $params['append_to_response']);
        }

        return $params;
    }
}
