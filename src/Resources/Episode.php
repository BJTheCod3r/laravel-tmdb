<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

use Carbon\CarbonImmutable;

class Episode extends Resource
{
    public ?int $id = null;

    public ?int $episodeNumber = null;

    public ?int $seasonNumber = null;

    public ?string $name = null;

    public ?string $overview = null;

    public ?CarbonImmutable $airDate = null;

    public ?int $runtime = null;

    public ?string $stillPath = null;

    public ?float $voteAverage = null;

    public ?int $voteCount = null;

    protected function hydrate(): void
    {
        $this->id = $this->get('id');
        $this->episodeNumber = $this->get('episode_number');
        $this->seasonNumber = $this->get('season_number');
        $this->name = $this->get('name');
        $this->overview = $this->get('overview');
        $this->airDate = $this->date('air_date');
        $this->runtime = $this->get('runtime');
        $this->stillPath = $this->get('still_path');
        $this->voteAverage = $this->get('vote_average');
        $this->voteCount = $this->get('vote_count');
    }
}
