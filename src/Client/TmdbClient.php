<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Client;

use BjTheCod3r\Tmdb\Exceptions\ApiException;
use BjTheCod3r\Tmdb\Exceptions\AuthenticationException;
use BjTheCod3r\Tmdb\Exceptions\RateLimitException;
use BjTheCod3r\Tmdb\Exceptions\ResourceNotFoundException;
use BjTheCod3r\Tmdb\Exceptions\TmdbException;
use BjTheCod3r\Tmdb\Exceptions\ValidationException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TmdbClient
{
    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(protected array $config)
    {
    }

    /**
     * @param array<string, mixed> $query
     * @return array<string, mixed>
     * @throws TmdbException
     */
    public function get(string $path, array $query = []): array
    {
        return $this->request('get', $path, $query);
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $query
     * @return array<string, mixed>
     * @throws TmdbException
     */
    public function post(string $path, array $payload = [], array $query = []): array
    {
        return $this->request('post', $path, $query, $payload);
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $query
     * @return array<string, mixed>
     * @throws TmdbException
     */
    public function delete(string $path, array $payload = [], array $query = []): array
    {
        return $this->request('delete', $path, $query, $payload);
    }

    /**
     * @param  array<string, mixed>  $query
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     * @throws TmdbException
     */
    protected function request(string $method, string $path, array $query = [], array $payload = []): array
    {
        $this->ensureCredentialsAreConfigured();

        // Only GET requests are retried: TMDB writes are not idempotent, so a
        // retry after a 5xx could apply the same change twice.
        $request = $this->pendingRequest(retries: $method === 'get')
            ->withQueryParameters($this->withDefaultQuery($path, $query));
        $url = $this->url($path);

        try {
            $response = $method === 'get'
                ? $request->get($url)
                : $request->{$method}($url, $payload);
        } catch (ConnectionException $e) {
            throw new ApiException('Could not connect to TMDB: '.$e->getMessage(), $e->getCode(), $e);
        }

        if ($response->failed()) {
            $this->throwFor($response);
        }

        return $response->json() ?? [];
    }

    protected function pendingRequest(bool $retries = true): PendingRequest
    {
        $request = Http::baseUrl($this->baseUrl())
            ->acceptJson()
            ->asJson()
            ->timeout((int) ($this->config['timeout'] ?? 10));

        if ($retries) {
            $request->retry(
                (int) ($this->config['retry']['times'] ?? 0),
                fn (int $attempt, $exception) => $this->retryDelay($exception),
                fn ($exception, $request) => $this->shouldRetry($exception),
                throw: false,
            );
        }

        if ($token = $this->config['token'] ?? null) {
            $request->withToken($token);
        }

        return $request;
    }

    /**
     * @throws AuthenticationException
     */
    protected function ensureCredentialsAreConfigured(): void
    {
        if (blank($this->config['token'] ?? null) && blank($this->config['api_key'] ?? null)) {
            throw new AuthenticationException(
                'No TMDB credentials configured. Set TMDB_TOKEN (v4 read access token) or TMDB_API_KEY in your environment.',
            );
        }
    }

    protected function shouldRetry(mixed $exception): bool
    {
        if ($exception instanceof ConnectionException) {
            return true;
        }

        $status = $exception instanceof RequestException ? $exception->response->status() : null;

        return $status === 429 || ($status !== null && $status >= 500);
    }

    /**
     * The delay (milliseconds) before the next retry attempt. Rate-limited
     * responses advertise a Retry-After header, which takes precedence over
     * the configured sleep.
     */
    protected function retryDelay(mixed $exception): int
    {
        if ($exception instanceof RequestException
            && ($retryAfter = $this->retryAfter($exception->response)) !== null) {
            return $retryAfter * 1000;
        }

        return (int) ($this->config['retry']['sleep'] ?? 0);
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    protected function withDefaultQuery(string $path, array $query): array
    {
        $defaults = collect([
            'language' => $this->config['language'] ?? null,
            'region' => $this->config['region'] ?? null,
        ])->filter(fn ($value) => filled($value));

        if ($this->supportsIncludeAdult($path)) {
            $defaults->put('include_adult', filter_var($this->config['include_adult'] ?? false, FILTER_VALIDATE_BOOL)
                ? 'true'
                : 'false');
        }

        if (blank($this->config['token'] ?? null) && filled($this->config['api_key'] ?? null)) {
            $defaults->put('api_key', $this->config['api_key']);
        }

        return $defaults->merge($query)->all();
    }

    /**
     * Whether the endpoint accepts the "include_adult" parameter (the search
     * and discover endpoint families).
     */
    protected function supportsIncludeAdult(string $path): bool
    {
        return Str::startsWith(ltrim($path, '/'), ['search/', 'discover/']);
    }

    protected function url(string $path): string
    {
        return Str::start($path, '/');
    }

    protected function baseUrl(): string
    {
        return rtrim($this->config['base_url'] ?? 'https://api.themoviedb.org/3', '/');
    }

    protected function throwFor(Response $response): never
    {
        $status = $response->status();
        $body = $response->json() ?? [];
        $message = $body['status_message'] ?? $response->reason() ?? 'TMDB request failed.';

        throw match ($status) {
            401 => new AuthenticationException($message, $status),
            404 => new ResourceNotFoundException($message, $status),
            400, 422 => new ValidationException($message, $status),
            429 => (new RateLimitException($message, $status))
                ->withRetryAfter($this->retryAfter($response)),
            default => new ApiException($message, $status),
        };
    }

    protected function retryAfter(Response $response): ?int
    {
        $header = $response->header('Retry-After');

        return is_numeric($header) ? (int) $header : null;
    }
}