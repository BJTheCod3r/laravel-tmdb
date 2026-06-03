<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

/**
 * A TMDB movie collection (e.g. "The Matrix Collection").
 */
class MovieCollection extends Resource
{
    public ?int $id = null;

    public ?string $name = null;

    public ?string $originalName = null;

    public ?string $overview = null;

    public ?string $posterPath = null;

    public ?string $backdropPath = null;

    public ?string $originalLanguage = null;

    public ?bool $adult = null;

    protected function hydrate(): void
    {
        $this->id = $this->get('id');
        $this->name = $this->get('name');
        $this->originalName = $this->get('original_name');
        $this->overview = $this->get('overview');
        $this->posterPath = $this->get('poster_path');
        $this->backdropPath = $this->get('backdrop_path');
        $this->originalLanguage = $this->get('original_language');
        $this->adult = $this->get('adult');
    }
}
