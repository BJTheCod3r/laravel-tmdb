<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Exceptions;

/**
 * Thrown when TMDB rejects the request as invalid (HTTP 422 / 400).
 */
class ValidationException extends TmdbException
{
}
