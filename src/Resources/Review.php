<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

use Carbon\CarbonImmutable;

class Review extends Resource
{
    public ?string $id = null;

    public ?string $author = null;

    public ?string $content = null;

    public ?string $url = null;

    public ?float $rating = null;

    public ?CarbonImmutable $createdAt = null;

    public ?CarbonImmutable $updatedAt = null;

    protected function hydrate(): void
    {
        $this->id = $this->get('id');
        $this->author = $this->get('author');
        $this->content = $this->get('content');
        $this->url = $this->get('url');
        $this->rating = $this->get('author_details.rating');
        $this->createdAt = $this->date('created_at');
        $this->updatedAt = $this->date('updated_at');
    }
}
