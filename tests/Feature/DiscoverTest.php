<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Tests\Feature;

use BjTheCod3r\Tmdb\Facades\Tmdb;
use BjTheCod3r\Tmdb\Resources\Movie;
use BjTheCod3r\Tmdb\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class DiscoverTest extends TestCase
{
    public function test_the_builder_assembles_filters_into_the_query_string(): void
    {
        Http::fake([
            'api.themoviedb.org/3/discover/movie*' => Http::response([
                'page' => 1,
                'results' => [['id' => 1, 'title' => 'A']],
                'total_pages' => 1,
                'total_results' => 1,
            ]),
        ]);

        $results = Tmdb::discover()->movies()
            ->withGenres([28, 12])
            ->year(2023)
            ->withMinimumVoteAverage(7.5)
            ->sortBy('popularity.desc')
            ->page(2)
            ->get();

        $this->assertInstanceOf(Movie::class, $results->results->first());

        Http::assertSent(function ($request) {
            $url = urldecode($request->url());

            return str_contains($url, 'with_genres=28,12')
                && str_contains($url, 'primary_release_year=2023')
                && str_contains($url, 'vote_average.gte=7.5')
                && str_contains($url, 'sort_by=popularity.desc')
                && str_contains($url, 'page=2');
        });
    }

    public function test_tv_discover_uses_first_air_date_year(): void
    {
        Http::fake(['*' => Http::response(['page' => 1, 'results' => [], 'total_pages' => 1, 'total_results' => 0])]);

        Tmdb::discover()->tv()->year(2020)->get();

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'discover/tv')
                && str_contains($request->url(), 'first_air_date_year=2020');
        });
    }
}
