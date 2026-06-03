<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

/**
 * A heterogeneous search/trending result whose shape depends on its
 * "media_type" ("movie", "tv" or "person"). Exposed as a thin typed wrapper:
 * {@see asResource()} promotes it to the matching concrete resource.
 */
class MediaResult extends Resource
{
    public ?int $id = null;

    public ?string $mediaType = null;

    protected function hydrate(): void
    {
        $this->id = $this->get('id');
        $this->mediaType = $this->get('media_type');
    }

    /**
     * Promote this result to its concrete resource type based on media_type.
     */
    public function asResource(): Resource
    {
        return match ($this->mediaType) {
            'movie' => Movie::make($this->attributes),
            'tv' => TvShow::make($this->attributes),
            'person' => Person::make($this->attributes),
            default => $this,
        };
    }
}
