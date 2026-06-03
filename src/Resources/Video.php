<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Resources;

class Video extends Resource
{
    public ?string $id = null;

    public ?string $name = null;

    public ?string $key = null;

    public ?string $site = null;

    public ?string $type = null;

    public ?int $size = null;

    public ?bool $official = null;

    protected function hydrate(): void
    {
        $this->id = $this->get('id');
        $this->name = $this->get('name');
        $this->key = $this->get('key');
        $this->site = $this->get('site');
        $this->type = $this->get('type');
        $this->size = $this->get('size');
        $this->official = $this->get('official');
    }

    /**
     * Convenience helper: the watchable URL for YouTube-hosted videos.
     */
    public function youtubeUrl(): ?string
    {
        if ($this->site === 'YouTube' && $this->key) {
            return "https://www.youtube.com/watch?v={$this->key}";
        }

        return null;
    }
}
