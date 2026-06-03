<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Tests\Feature;

use BjTheCod3r\Tmdb\Facades\Tmdb;
use BjTheCod3r\Tmdb\Resources\Movie;
use BjTheCod3r\Tmdb\Resources\Paginated;
use BjTheCod3r\Tmdb\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class MoviesTest extends TestCase
{
    public function test_it_fetches_movie_details_as_a_typed_resource(): void
    {
        Http::fake([
            'api.themoviedb.org/3/movie/27205*' => Http::response([
                'id' => 27205,
                'title' => 'Inception',
                'release_date' => '2010-07-15',
                'runtime' => 148,
                'vote_average' => 8.4,
                'genres' => [['id' => 28, 'name' => 'Action']],
            ]),
        ]);

        $movie = Tmdb::movies()->details(27205);

        $this->assertInstanceOf(Movie::class, $movie);
        $this->assertSame('Inception', $movie->title);
        $this->assertSame(148, $movie->runtime);
        $this->assertSame(2010, $movie->year());
        $this->assertSame('Action', $movie->genres->first()->name);
    }

    public function test_it_sends_the_bearer_token_and_default_language(): void
    {
        Http::fake([
            '*' => Http::response(['id' => 1, 'title' => 'Test']),
        ]);

        Tmdb::movies()->details(1);

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization', 'Bearer test-read-access-token')
                && str_contains($request->url(), 'language=en-US');
        });
    }

    public function test_it_returns_a_paginated_list_of_popular_movies(): void
    {
        Http::fake([
            'api.themoviedb.org/3/movie/popular*' => Http::response([
                'page' => 1,
                'results' => [
                    ['id' => 1, 'title' => 'A'],
                    ['id' => 2, 'title' => 'B'],
                ],
                'total_pages' => 5,
                'total_results' => 100,
            ]),
        ]);

        $movies = Tmdb::movies()->popular();

        $this->assertInstanceOf(Paginated::class, $movies);
        $this->assertCount(2, $movies->results);
        $this->assertTrue($movies->hasMorePages());
        $this->assertSame(2, $movies->nextPage());
        $this->assertInstanceOf(Movie::class, $movies->results->first());
    }

    public function test_it_normalizes_append_to_response_arrays(): void
    {
        Http::fake([
            '*' => Http::response(['id' => 1, 'title' => 'Test']),
        ]);

        Tmdb::movies()->details(1, ['append_to_response' => ['credits', 'videos']]);

        Http::assertSent(fn ($request) => str_contains($request->url(), 'append_to_response=credits%2Cvideos'));
    }
}
