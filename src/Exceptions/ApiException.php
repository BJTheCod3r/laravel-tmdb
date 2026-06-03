<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Exceptions;

/**
 * Thrown for any TMDB error response not covered by a more specific
 * exception (e.g. unexpected 5xx failures).
 */
class ApiException extends TmdbException
{
}
