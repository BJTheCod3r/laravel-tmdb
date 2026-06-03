<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Tests\Feature;

use BjTheCod3r\Tmdb\Facades\Tmdb;
use BjTheCod3r\Tmdb\Resources\Movie;
use BjTheCod3r\Tmdb\Resources\TvShow;
use BjTheCod3r\Tmdb\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class SearchTest extends TestCase
{
    public function test_it_searches_movies_and_passes_the_query(): void
    {
        Http::fake([
            'api.themoviedb.org/3/search/movie*' => Http::response([
                'page' => 1,
                'results' => [['id' => 27205, 'title' => 'Inception']],
                'total_pages' => 1,
                'total_results' => 1,
            ]),
        ]);

        $results = Tmdb::search()->movies('inception', ['year' => 2010]);

        $this->assertInstanceOf(Movie::class, $results->results->first());
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'query=inception')
                && str_contains($request->url(), 'year=2010');
        });
    }

    public function test_multi_search_promotes_results_to_typed_resources(): void
    {
        Http::fake([
            'api.themoviedb.org/3/search/multi*' => Http::response([
                'page' => 1,
                'results' => [
                    ['id' => 1, 'media_type' => 'movie', 'title' => 'A Movie'],
                    ['id' => 2, 'media_type' => 'tv', 'name' => 'A Show'],
                ],
                'total_pages' => 1,
                'total_results' => 2,
            ]),
        ]);

        $results = Tmdb::search()->multi('matrix');

        $this->assertInstanceOf(Movie::class, $results->results[0]->asResource());
        $this->assertInstanceOf(TvShow::class, $results->results[1]->asResource());
    }
}
