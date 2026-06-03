<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Tests\Unit;

use BjTheCod3r\Tmdb\Support\ImageUrl;
use PHPUnit\Framework\TestCase;

class ImageUrlTest extends TestCase
{
    public function test_it_builds_sized_urls(): void
    {
        $image = new ImageUrl;

        $this->assertSame(
            'https://image.tmdb.org/t/p/w500/poster.jpg',
            $image->url('/poster.jpg', 'w500'),
        );
    }

    public function test_it_defaults_to_original(): void
    {
        $image = new ImageUrl;

        $this->assertSame(
            'https://image.tmdb.org/t/p/original/backdrop.jpg',
            $image->original('backdrop.jpg'),
        );
    }

    public function test_it_returns_null_for_missing_paths(): void
    {
        $image = new ImageUrl;

        $this->assertNull($image->url(null, 'w500'));
        $this->assertNull($image->url('', 'w500'));
    }
}
