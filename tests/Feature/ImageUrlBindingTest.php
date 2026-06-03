<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Tests\Feature;

use BjTheCod3r\Tmdb\Facades\Tmdb;
use BjTheCod3r\Tmdb\Tests\TestCase;

class ImageUrlBindingTest extends TestCase
{
    public function test_the_image_helper_uses_the_configured_base_url(): void
    {
        config()->set('tmdb.image_base_url', 'https://cdn.example.test/img/');

        $this->assertSame(
            'https://cdn.example.test/img/w500/poster.jpg',
            Tmdb::image()->url('/poster.jpg', 'w500'),
        );
    }
}
