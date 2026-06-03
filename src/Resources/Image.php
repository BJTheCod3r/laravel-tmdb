<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

class Image extends Resource
{
    public ?string $filePath = null;

    public ?float $aspectRatio = null;

    public ?int $height = null;

    public ?int $width = null;

    public ?float $voteAverage = null;

    public ?int $voteCount = null;

    public ?string $iso6391 = null;

    protected function hydrate(): void
    {
        $this->filePath = $this->get('file_path');
        $this->aspectRatio = $this->get('aspect_ratio');
        $this->height = $this->get('height');
        $this->width = $this->get('width');
        $this->voteAverage = $this->get('vote_average');
        $this->voteCount = $this->get('vote_count');
        $this->iso6391 = $this->get('iso_639_1');
    }
}
