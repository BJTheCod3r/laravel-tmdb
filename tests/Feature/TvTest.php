<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Tests\Feature;

use BjTheCod3r\Tmdb\Facades\Tmdb;
use BjTheCod3r\Tmdb\Resources\Episode;
use BjTheCod3r\Tmdb\Resources\Season;
use BjTheCod3r\Tmdb\Resources\TvShow;
use BjTheCod3r\Tmdb\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class TvTest extends TestCase
{
    public function test_it_fetches_tv_details_as_a_typed_resource(): void
    {
        Http::fake([
            'api.themoviedb.org/3/tv/1396*' => Http::response([
                'id' => 1396,
                'name' => 'Breaking Bad',
                'first_air_date' => '2008-01-20',
                'number_of_seasons' => 5,
                'genres' => [['id' => 18, 'name' => 'Drama']],
            ]),
        ]);

        $show = Tmdb::tv()->details(1396);

        $this->assertInstanceOf(TvShow::class, $show);
        $this->assertSame('Breaking Bad', $show->name);
        $this->assertSame(5, $show->numberOfSeasons);
        $this->assertSame(2008, $show->firstAirDate?->year);
        $this->assertSame('Drama', $show->genres->first()->name);
    }

    public function test_it_fetches_seasons_and_episodes(): void
    {
        Http::fake([
            'api.themoviedb.org/3/tv/1396/season/1/episode/1*' => Http::response([
                'id' => 62085,
                'episode_number' => 1,
                'season_number' => 1,
                'name' => 'Pilot',
            ]),
            'api.themoviedb.org/3/tv/1396/season/1*' => Http::response([
                'id' => 3572,
                'season_number' => 1,
                'episodes' => [['id' => 62085, 'episode_number' => 1, 'name' => 'Pilot']],
            ]),
        ]);

        $episode = Tmdb::tv()->episode(1396, 1, 1);
        $this->assertInstanceOf(Episode::class, $episode);
        $this->assertSame('Pilot', $episode->name);

        $season = Tmdb::tv()->season(1396, 1);
        $this->assertInstanceOf(Season::class, $season);
        $this->assertInstanceOf(Episode::class, $season->episodes->first());
    }

    public function test_it_unwraps_watch_provider_results(): void
    {
        Http::fake([
            'api.themoviedb.org/3/tv/1396/watch/providers*' => Http::response([
                'id' => 1396,
                'results' => ['US' => ['link' => 'https://example.test']],
            ]),
        ]);

        $providers = Tmdb::tv()->watchProviders(1396);

        $this->assertArrayHasKey('US', $providers);
    }
}
