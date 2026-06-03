<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Tests\Feature;

use BjTheCod3r\Tmdb\Facades\Tmdb;
use BjTheCod3r\Tmdb\Resources\Genre;
use BjTheCod3r\Tmdb\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class GenresTest extends TestCase
{
    public function test_it_fetches_movie_and_tv_genres_as_collections(): void
    {
        Http::fake([
            'api.themoviedb.org/3/genre/movie/list*' => Http::response([
                'genres' => [['id' => 28, 'name' => 'Action']],
            ]),
            'api.themoviedb.org/3/genre/tv/list*' => Http::response([
                'genres' => [['id' => 18, 'name' => 'Drama']],
            ]),
        ]);

        $movieGenres = Tmdb::genres()->movies();
        $this->assertInstanceOf(Genre::class, $movieGenres->first());
        $this->assertSame('Action', $movieGenres->first()->name);

        $tvGenres = Tmdb::genres()->tv();
        $this->assertSame('Drama', $tvGenres->first()->name);
    }
}
