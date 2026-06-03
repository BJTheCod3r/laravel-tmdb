<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Tests\Unit;

use BjTheCod3r\Tmdb\Resources\Movie;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{
    public function test_malformed_dates_hydrate_to_null(): void
    {
        $movie = Movie::make(['id' => 1, 'release_date' => 'not-a-date']);

        $this->assertNull($movie->releaseDate);
        $this->assertNull($movie->year());
    }

    public function test_empty_dates_hydrate_to_null(): void
    {
        $movie = Movie::make(['id' => 1, 'release_date' => '']);

        $this->assertNull($movie->releaseDate);
    }

    public function test_isset_matches_array_access_semantics(): void
    {
        $movie = Movie::make(['id' => 1, 'belongs_to_collection' => ['name' => 'A Collection']]);

        $this->assertTrue(isset($movie->{'belongs_to_collection.name'}));
        $this->assertTrue(isset($movie['belongs_to_collection.name']));
        $this->assertFalse(isset($movie->missing));
        $this->assertFalse(isset($movie['missing']));
    }
}
