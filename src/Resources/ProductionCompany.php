<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

class ProductionCompany extends Resource
{
    public ?int $id = null;

    public ?string $name = null;

    public ?string $logoPath = null;

    public ?string $originCountry = null;

    protected function hydrate(): void
    {
        $this->id = $this->get('id');
        $this->name = $this->get('name');
        $this->logoPath = $this->get('logo_path');
        $this->originCountry = $this->get('origin_country');
    }
}
