<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Exceptions;

/**
 * Thrown when TMDB rate limiting kicks in (HTTP 429) and the configured
 * retries have been exhausted.
 */
class RateLimitException extends TmdbException
{
    /**
     * Number of seconds the caller should wait before retrying, as reported
     * by the TMDB "Retry-After" response header (null when not provided).
     */
    public ?int $retryAfter = null;

    public function withRetryAfter(?int $seconds): self
    {
        $this->retryAfter = $seconds;

        return $this;
    }
}
