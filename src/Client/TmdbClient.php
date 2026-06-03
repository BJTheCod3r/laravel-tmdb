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
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

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
        $request = $this->pendingRequest()->withQueryParameters($this->withDefaultQuery($query));
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

    protected function pendingRequest(): PendingRequest
    {
        $request = Http::baseUrl($this->baseUrl())
            ->acceptJson()
            ->asJson()
            ->timeout((int) ($this->config['timeout'] ?? 10))
            ->retry(
                (int) ($this->config['retry']['times'] ?? 0),
                (int) ($this->config['retry']['sleep'] ?? 0),
                fn ($exception, $request) => $this->shouldRetry($exception),
                throw: false,
            );

        if ($token = $this->config['token'] ?? null) {
            $request->withToken($token);
        }

        return $request;
    }

    protected function shouldRetry(mixed $exception): bool
    {
        if ($exception instanceof ConnectionException) {
            return true;
        }

        $status = $exception instanceof RequestException ? $exception->response->status() : null;

        return $status === JsonResponse::HTTP_TOO_MANY_REQUESTS
            || ($status !== null && $status >= JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    protected function withDefaultQuery(array $query): array
    {
        $defaults = array_filter([
            'language' => $this->config['language'] ?? null,
            'region' => $this->config['region'] ?? null,
        ], fn ($value) => $value !== null && $value !== '');

        if (empty($this->config['token']) && ! empty($this->config['api_key'])) {
            $defaults['api_key'] = $this->config['api_key'];
        }

        return array_merge($defaults, $query);
    }

    protected function url(string $path): string
    {
        return '/'.ltrim($path, '/');
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

        throw match (true) {
            $status === JsonResponse::HTTP_UNAUTHORIZED => new AuthenticationException($message, $status),
            $status === JsonResponse::HTTP_NOT_FOUND => new ResourceNotFoundException($message, $status),
            $status === JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            $status === JsonResponse::HTTP_BAD_REQUEST => new ValidationException($message, $status),
            $status === JsonResponse::HTTP_TOO_MANY_REQUESTS => (new RateLimitException($message, $status))
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
