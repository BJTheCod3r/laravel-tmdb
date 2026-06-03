<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Tests\Feature;

use BjTheCod3r\Tmdb\Facades\Tmdb;
use BjTheCod3r\Tmdb\Resources\Keyword;
use BjTheCod3r\Tmdb\Resources\MediaResult;
use BjTheCod3r\Tmdb\Resources\Movie;
use BjTheCod3r\Tmdb\Resources\MovieCollection;
use BjTheCod3r\Tmdb\Resources\ProductionCompany;
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

    public function test_media_results_with_unknown_media_types_stay_as_media_results(): void
    {
        $result = MediaResult::make(['id' => 1, 'media_type' => 'collection']);

        $this->assertSame($result, $result->asResource());
    }

    public function test_search_sends_the_configured_include_adult_default(): void
    {
        Http::fake(['*' => Http::response(['page' => 1, 'results' => [], 'total_pages' => 1, 'total_results' => 0])]);

        Tmdb::search()->movies('test');

        Http::assertSent(fn ($request) => str_contains($request->url(), 'include_adult=false'));
    }

    public function test_include_adult_config_can_be_enabled_and_overridden_per_call(): void
    {
        config()->set('tmdb.include_adult', true);

        Http::fake(['*' => Http::response(['page' => 1, 'results' => [], 'total_pages' => 1, 'total_results' => 0])]);

        Tmdb::search()->movies('test');
        Http::assertSent(fn ($request) => str_contains($request->url(), 'include_adult=true'));

        Tmdb::search()->movies('test', ['include_adult' => 'false']);
        Http::assertSent(fn ($request) => str_contains($request->url(), 'include_adult=false'));
    }

    public function test_include_adult_is_not_sent_on_non_search_endpoints(): void
    {
        Http::fake(['*' => Http::response(['id' => 1, 'title' => 'Test'])]);

        Tmdb::movies()->details(1);

        Http::assertSent(fn ($request) => ! str_contains($request->url(), 'include_adult'));
    }

    public function test_company_collection_and_keyword_searches_return_typed_pages(): void
    {
        Http::fake([
            'api.themoviedb.org/3/search/company*' => Http::response([
                'page' => 1,
                'results' => [['id' => 1, 'name' => 'Warner Bros.', 'origin_country' => 'US']],
                'total_pages' => 1,
                'total_results' => 1,
            ]),
            'api.themoviedb.org/3/search/collection*' => Http::response([
                'page' => 1,
                'results' => [['id' => 2344, 'name' => 'The Matrix Collection', 'poster_path' => '/p.jpg']],
                'total_pages' => 1,
                'total_results' => 1,
            ]),
            'api.themoviedb.org/3/search/keyword*' => Http::response([
                'page' => 1,
                'results' => [['id' => 9715, 'name' => 'superhero']],
                'total_pages' => 1,
                'total_results' => 1,
            ]),
        ]);

        $company = Tmdb::search()->companies('warner')->results->first();
        $this->assertInstanceOf(ProductionCompany::class, $company);
        $this->assertSame('Warner Bros.', $company->name);

        $collection = Tmdb::search()->collections('matrix')->results->first();
        $this->assertInstanceOf(MovieCollection::class, $collection);
        $this->assertSame('/p.jpg', $collection->posterPath);

        $keyword = Tmdb::search()->keywords('superhero')->results->first();
        $this->assertInstanceOf(Keyword::class, $keyword);
        $this->assertSame('superhero', $keyword->name);
    }
}
