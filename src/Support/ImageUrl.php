<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Support;

/**
 * Builds absolute TMDB image URLs from the relative file paths returned on
 * resources. TMDB serves images from an image CDN at a set of named sizes;
 * see /configuration for the authoritative list.
 *
 *     Tmdb::image()->url($movie->posterPath, 'w500');
 *     Tmdb::image()->original($movie->backdropPath);
 */
class ImageUrl
{
    public function __construct(
        protected string $baseUrl = 'https://image.tmdb.org/t/p/',
    ) {
    }

    /**
     * Build a URL for the given path at a named size (e.g. "w200", "w500",
     * "original"). Returns null when the path itself is null/empty.
     */
    public function url(?string $path, string $size = 'original'): ?string
    {
        if (empty($path)) {
            return null;
        }

        return rtrim($this->baseUrl, '/').'/'.trim($size, '/').'/'.ltrim($path, '/');
    }

    public function original(?string $path): ?string
    {
        return $this->url($path, 'original');
    }
}
