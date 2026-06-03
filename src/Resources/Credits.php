<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

use Illuminate\Support\Collection;

class Credits extends Resource
{
    public ?int $id = null;

    /** @var Collection<int, CastMember> */
    public Collection $cast;

    /** @var Collection<int, CrewMember> */
    public Collection $crew;

    protected function hydrate(): void
    {
        $this->id = $this->get('id');
        $this->cast = CastMember::collection($this->get('cast', []));
        $this->crew = CrewMember::collection($this->get('crew', []));
    }
}
