<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Tests\Feature;

use BjTheCod3r\Tmdb\Facades\Tmdb;
use BjTheCod3r\Tmdb\Resources\Image;
use BjTheCod3r\Tmdb\Resources\Person;
use BjTheCod3r\Tmdb\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class PeopleTest extends TestCase
{
    public function test_it_fetches_person_details_as_a_typed_resource(): void
    {
        Http::fake([
            'api.themoviedb.org/3/person/6193*' => Http::response([
                'id' => 6193,
                'name' => 'Leonardo DiCaprio',
                'birthday' => '1974-11-11',
                'known_for_department' => 'Acting',
            ]),
        ]);

        $person = Tmdb::people()->details(6193);

        $this->assertInstanceOf(Person::class, $person);
        $this->assertSame('Leonardo DiCaprio', $person->name);
        $this->assertSame(1974, $person->birthday?->year);
    }

    public function test_it_maps_profile_images(): void
    {
        Http::fake([
            'api.themoviedb.org/3/person/6193/images*' => Http::response([
                'id' => 6193,
                'profiles' => [['file_path' => '/profile.jpg', 'width' => 300]],
            ]),
        ]);

        $images = Tmdb::people()->images(6193);

        $this->assertInstanceOf(Image::class, $images->first());
        $this->assertSame('/profile.jpg', $images->first()->filePath);
    }

    public function test_popular_returns_paginated_people(): void
    {
        Http::fake([
            'api.themoviedb.org/3/person/popular*' => Http::response([
                'page' => 1,
                'results' => [['id' => 6193, 'name' => 'Leonardo DiCaprio']],
                'total_pages' => 1,
                'total_results' => 1,
            ]),
        ]);

        $people = Tmdb::people()->popular();

        $this->assertInstanceOf(Person::class, $people->results->first());
    }
}
