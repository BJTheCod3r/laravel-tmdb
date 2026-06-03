<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

class Keyword extends Resource
{
    public ?int $id = null;

    public ?string $name = null;

    protected function hydrate(): void
    {
        $this->id = $this->get('id');
        $this->name = $this->get('name');
    }
}
