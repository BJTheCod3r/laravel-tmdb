<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class Season extends Resource
{
    public ?int $id = null;

    public ?int $seasonNumber = null;

    public ?string $name = null;

    public ?string $overview = null;

    public ?CarbonImmutable $airDate = null;

    public ?int $episodeCount = null;

    public ?string $posterPath = null;

    public ?float $voteAverage = null;

    /** @var Collection<int, Episode> */
    public Collection $episodes;

    protected function hydrate(): void
    {
        $this->id = $this->get('id');
        $this->seasonNumber = $this->get('season_number');
        $this->name = $this->get('name');
        $this->overview = $this->get('overview');
        $this->airDate = $this->date('air_date');
        $this->episodeCount = $this->get('episode_count');
        $this->posterPath = $this->get('poster_path');
        $this->voteAverage = $this->get('vote_average');
        $this->episodes = Episode::collection($this->get('episodes', []));
    }
}
