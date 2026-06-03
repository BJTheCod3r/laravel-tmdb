<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Exceptions;

/**
 * Thrown when TMDB rejects the request credentials (HTTP 401) or no
 * credentials were configured at all.
 */
class AuthenticationException extends TmdbException
{
}
