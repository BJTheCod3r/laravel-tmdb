<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Exceptions;

/**
 * Thrown when TMDB cannot find the requested resource (HTTP 404).
 */
class ResourceNotFoundException extends TmdbException
{
}
