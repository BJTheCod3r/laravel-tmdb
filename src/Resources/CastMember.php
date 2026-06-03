<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

class CastMember extends Resource
{
    public ?int $id = null;

    public ?string $name = null;

    public ?string $character = null;

    public ?int $order = null;

    public ?string $profilePath = null;

    public ?string $knownForDepartment = null;

    public ?float $popularity = null;

    protected function hydrate(): void
    {
        $this->id = $this->get('id');
        $this->name = $this->get('name');
        $this->character = $this->get('character');
        $this->order = $this->get('order');
        $this->profilePath = $this->get('profile_path');
        $this->knownForDepartment = $this->get('known_for_department');
        $this->popularity = $this->get('popularity');
    }
}
