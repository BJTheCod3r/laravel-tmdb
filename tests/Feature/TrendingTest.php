<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Tests\Feature;

use BjTheCod3r\Tmdb\Facades\Tmdb;
use BjTheCod3r\Tmdb\Resources\MediaResult;
use BjTheCod3r\Tmdb\Resources\Movie;
use BjTheCod3r\Tmdb\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class TrendingTest extends TestCase
{
    public function test_it_fetches_trending_movies_for_the_given_window(): void
    {
        Http::fake([
            'api.themoviedb.org/3/trending/movie/week*' => Http::response([
                'page' => 1,
                'results' => [['id' => 1, 'title' => 'A']],
                'total_pages' => 1,
                'total_results' => 1,
            ]),
        ]);

        $movies = Tmdb::trending()->movies('week');

        $this->assertInstanceOf(Movie::class, $movies->results->first());
        Http::assertSent(fn ($request) => str_contains($request->url(), 'trending/movie/week'));
    }

    public function test_trending_all_returns_media_results(): void
    {
        Http::fake([
            'api.themoviedb.org/3/trending/all/day*' => Http::response([
                'page' => 1,
                'results' => [['id' => 1, 'media_type' => 'movie', 'title' => 'A']],
                'total_pages' => 1,
                'total_results' => 1,
            ]),
        ]);

        $results = Tmdb::trending()->all();

        $this->assertInstanceOf(MediaResult::class, $results->results->first());
        $this->assertInstanceOf(Movie::class, $results->results->first()->asResource());
    }
}
