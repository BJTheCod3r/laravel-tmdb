<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

use Carbon\CarbonImmutable;

class Person extends Resource
{
    public ?int $id = null;

    public ?string $name = null;

    public ?string $biography = null;

    public ?string $knownForDepartment = null;

    public ?CarbonImmutable $birthday = null;

    public ?CarbonImmutable $deathday = null;

    public ?string $placeOfBirth = null;

    public ?int $gender = null;

    public ?float $popularity = null;

    public ?string $profilePath = null;

    public ?string $homepage = null;

    public ?string $imdbId = null;

    /** @var array<int, string> */
    public array $alsoKnownAs = [];

    protected function hydrate(): void
    {
        $this->id = $this->get('id');
        $this->name = $this->get('name');
        $this->biography = $this->get('biography');
        $this->knownForDepartment = $this->get('known_for_department');
        $this->birthday = $this->date('birthday');
        $this->deathday = $this->date('deathday');
        $this->placeOfBirth = $this->get('place_of_birth');
        $this->gender = $this->get('gender');
        $this->popularity = $this->get('popularity');
        $this->profilePath = $this->get('profile_path');
        $this->homepage = $this->get('homepage');
        $this->imdbId = $this->get('imdb_id');
        $this->alsoKnownAs = $this->get('also_known_as', []);
    }
}
