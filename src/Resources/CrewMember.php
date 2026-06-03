<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

class CrewMember extends Resource
{
    public ?int $id = null;

    public ?string $name = null;

    public ?string $job = null;

    public ?string $department = null;

    public ?string $profilePath = null;

    public ?string $knownForDepartment = null;

    public ?float $popularity = null;

    protected function hydrate(): void
    {
        $this->id = $this->get('id');
        $this->name = $this->get('name');
        $this->job = $this->get('job');
        $this->department = $this->get('department');
        $this->profilePath = $this->get('profile_path');
        $this->knownForDepartment = $this->get('known_for_department');
        $this->popularity = $this->get('popularity');
    }
}
