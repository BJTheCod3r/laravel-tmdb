<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Tests\Feature;

use BjTheCod3r\Tmdb\Exceptions\AuthenticationException;
use BjTheCod3r\Tmdb\Exceptions\RateLimitException;
use BjTheCod3r\Tmdb\Exceptions\ResourceNotFoundException;
use BjTheCod3r\Tmdb\Exceptions\ValidationException;
use BjTheCod3r\Tmdb\Facades\Tmdb;
use BjTheCod3r\Tmdb\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ClientTest extends TestCase
{
    public function test_it_throws_a_not_found_exception_on_404(): void
    {
        Http::fake([
            '*' => Http::response(['status_message' => 'The resource you requested could not be found.'], 404),
        ]);

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage('could not be found');

        Tmdb::movies()->details(999999999);
    }

    public function test_it_throws_an_authentication_exception_on_401(): void
    {
        Http::fake([
            '*' => Http::response(['status_message' => 'Invalid API key.'], 401),
        ]);

        $this->expectException(AuthenticationException::class);

        Tmdb::movies()->details(1);
    }

    public function test_it_exposes_retry_after_on_rate_limit(): void
    {
        Http::fake([
            '*' => Http::response(['status_message' => 'Too many requests.'], 429, ['Retry-After' => '7']),
        ]);

        try {
            Tmdb::movies()->details(1);
            $this->fail('Expected a RateLimitException.');
        } catch (RateLimitException $e) {
            $this->assertSame(7, $e->retryAfter);
        }
    }

    public function test_it_throws_a_validation_exception_on_422(): void
    {
        Http::fake([
            '*' => Http::response(['status_message' => 'page must be less than or equal to 500'], 422),
        ]);

        $this->expectException(ValidationException::class);

        Tmdb::movies()->popular(['page' => 501]);
    }

    public function test_it_retries_transient_server_errors(): void
    {
        config()->set('tmdb.retry.times', 2);
        config()->set('tmdb.retry.sleep', 0);

        Http::fake([
            '*' => Http::sequence()
                ->push(['status_message' => 'Internal error'], 500)
                ->push(['id' => 1, 'title' => 'Recovered']),
        ]);

        $movie = Tmdb::movies()->details(1);

        $this->assertSame('Recovered', $movie->title);
        Http::assertSentCount(2);
    }

    public function test_it_does_not_retry_client_errors(): void
    {
        config()->set('tmdb.retry.times', 2);
        config()->set('tmdb.retry.sleep', 0);

        Http::fake([
            '*' => Http::response(['status_message' => 'Not found'], 404),
        ]);

        try {
            Tmdb::movies()->details(1);
            $this->fail('Expected a ResourceNotFoundException.');
        } catch (ResourceNotFoundException) {
            Http::assertSentCount(1);
        }
    }

    public function test_it_falls_back_to_api_key_when_no_token_is_set(): void
    {
        config()->set('tmdb.token', null);
        config()->set('tmdb.api_key', 'my-v3-key');

        Http::fake(['*' => Http::response(['id' => 1, 'title' => 'Test'])]);

        Tmdb::movies()->details(1);

        Http::assertSent(function ($request) {
            return ! $request->hasHeader('Authorization')
                && str_contains($request->url(), 'api_key=my-v3-key');
        });
    }
}
