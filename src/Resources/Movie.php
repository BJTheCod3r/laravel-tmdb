<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class Movie extends Resource
{
    public ?int $id = null;

    public ?string $title = null;

    public ?string $originalTitle = null;

    public ?string $overview = null;

    public ?string $tagline = null;

    public ?CarbonImmutable $releaseDate = null;

    public ?int $runtime = null;

    public ?string $status = null;

    public ?float $voteAverage = null;

    public ?int $voteCount = null;

    public ?float $popularity = null;

    public ?bool $adult = null;

    public ?string $posterPath = null;

    public ?string $backdropPath = null;

    public ?string $originalLanguage = null;

    public ?int $budget = null;

    public ?int $revenue = null;

    public ?string $homepage = null;

    public ?string $imdbId = null;

    /** @var Collection<int, Genre> */
    public Collection $genres;

    /** @var Collection<int, ProductionCompany> */
    public Collection $productionCompanies;

    protected function hydrate(): void
    {
        $this->id = $this->get('id');
        $this->title = $this->get('title');
        $this->originalTitle = $this->get('original_title');
        $this->overview = $this->get('overview');
        $this->tagline = $this->get('tagline');
        $this->releaseDate = $this->date('release_date');
        $this->runtime = $this->get('runtime');
        $this->status = $this->get('status');
        $this->voteAverage = $this->get('vote_average');
        $this->voteCount = $this->get('vote_count');
        $this->popularity = $this->get('popularity');
        $this->adult = $this->get('adult');
        $this->posterPath = $this->get('poster_path');
        $this->backdropPath = $this->get('backdrop_path');
        $this->originalLanguage = $this->get('original_language');
        $this->budget = $this->get('budget');
        $this->revenue = $this->get('revenue');
        $this->homepage = $this->get('homepage');
        $this->imdbId = $this->get('imdb_id');
        $this->genres = Genre::collection($this->get('genres', []));
        $this->productionCompanies = ProductionCompany::collection($this->get('production_companies', []));
    }

    public function year(): ?int
    {
        return $this->releaseDate?->year;
    }
}
