<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class TvShow extends Resource
{
    public ?int $id = null;

    public ?string $name = null;

    public ?string $originalName = null;

    public ?string $overview = null;

    public ?string $tagline = null;

    public ?CarbonImmutable $firstAirDate = null;

    public ?CarbonImmutable $lastAirDate = null;

    public ?string $status = null;

    public ?bool $inProduction = null;

    public ?int $numberOfSeasons = null;

    public ?int $numberOfEpisodes = null;

    public ?float $voteAverage = null;

    public ?int $voteCount = null;

    public ?float $popularity = null;

    public ?string $posterPath = null;

    public ?string $backdropPath = null;

    public ?string $originalLanguage = null;

    public ?string $homepage = null;

    /** @var array<int, string> */
    public array $originCountry = [];

    /** @var array<int, string> */
    public array $episodeRunTime = [];

    /** @var Collection<int, Genre> */
    public Collection $genres;

    /** @var Collection<int, ProductionCompany> */
    public Collection $productionCompanies;

    /** @var Collection<int, Season> */
    public Collection $seasons;

    protected function hydrate(): void
    {
        $this->id = $this->get('id');
        $this->name = $this->get('name');
        $this->originalName = $this->get('original_name');
        $this->overview = $this->get('overview');
        $this->tagline = $this->get('tagline');
        $this->firstAirDate = $this->date('first_air_date');
        $this->lastAirDate = $this->date('last_air_date');
        $this->status = $this->get('status');
        $this->inProduction = $this->get('in_production');
        $this->numberOfSeasons = $this->get('number_of_seasons');
        $this->numberOfEpisodes = $this->get('number_of_episodes');
        $this->voteAverage = $this->get('vote_average');
        $this->voteCount = $this->get('vote_count');
        $this->popularity = $this->get('popularity');
        $this->posterPath = $this->get('poster_path');
        $this->backdropPath = $this->get('backdrop_path');
        $this->originalLanguage = $this->get('original_language');
        $this->homepage = $this->get('homepage');
        $this->originCountry = $this->get('origin_country', []);
        $this->episodeRunTime = $this->get('episode_run_time', []);
        $this->genres = Genre::collection($this->get('genres', []));
        $this->productionCompanies = ProductionCompany::collection($this->get('production_companies', []));
        $this->seasons = Season::collection($this->get('seasons', []));
    }
}
