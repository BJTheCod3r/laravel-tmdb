<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Tests\Feature;

use BjTheCod3r\Tmdb\Facades\Tmdb;
use BjTheCod3r\Tmdb\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ConfigurationTest extends TestCase
{
    public function test_it_fetches_the_system_configuration(): void
    {
        Http::fake([
            'api.themoviedb.org/3/configuration*' => Http::response([
                'images' => ['secure_base_url' => 'https://image.tmdb.org/t/p/'],
                'change_keys' => [],
            ]),
        ]);

        $config = Tmdb::configuration()->details();

        $this->assertSame('https://image.tmdb.org/t/p/', $config['images']['secure_base_url']);
    }

    public function test_it_fetches_countries(): void
    {
        Http::fake([
            'api.themoviedb.org/3/configuration/countries*' => Http::response([
                ['iso_3166_1' => 'US', 'english_name' => 'United States of America'],
            ]),
        ]);

        $countries = Tmdb::configuration()->countries();

        $this->assertSame('US', $countries[0]['iso_3166_1']);
    }
}
