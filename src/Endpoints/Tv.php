<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Endpoints;

use BjTheCod3r\Tmdb\Exceptions\TmdbException;
use BjTheCod3r\Tmdb\Resources\Credits;
use BjTheCod3r\Tmdb\Resources\Episode;
use BjTheCod3r\Tmdb\Resources\Image;
use BjTheCod3r\Tmdb\Resources\Paginated;
use BjTheCod3r\Tmdb\Resources\Season;
use BjTheCod3r\Tmdb\Resources\TvShow;
use BjTheCod3r\Tmdb\Resources\Video;
use Illuminate\Support\Collection;

/**
 * The /tv endpoints.
 *
 * @see https://developer.themoviedb.org/reference/tv-series-details
 */
class Tv extends Endpoint
{
    /**
     * Get the primary information for a TV series.
     *
     * @param  array<string, mixed>  $params  e.g. ['append_to_response' => ['credits']].
     * @throws TmdbException
     */
    public function details(int $id, array $params = []): TvShow
    {
        return TvShow::make($this->client->get("tv/{$id}", $this->normalizeAppend($params)));
    }

    /**
     * Get the details of a single season of a TV series.
     * @throws TmdbException
     */
    public function season(int $tvId, int $seasonNumber, array $params = []): Season
    {
        return Season::make($this->client->get("tv/{$tvId}/season/{$seasonNumber}", $this->normalizeAppend($params)));
    }

    /**
     * Get the details of a single episode of a TV series.
     * @throws TmdbException
     */
    public function episode(int $tvId, int $seasonNumber, int $episodeNumber, array $params = []): Episode
    {
        return Episode::make($this->client->get(
            "tv/{$tvId}/season/{$seasonNumber}/episode/{$episodeNumber}",
            $this->normalizeAppend($params),
        ));
    }

    /**
     * Get the aggregate cast and crew for a TV series.
     * @throws TmdbException
     */
    public function credits(int $id, array $params = []): Credits
    {
        return Credits::make($this->client->get("tv/{$id}/credits", $params));
    }

    /**
     * @return Collection<int, Image>
     * @throws TmdbException
     */
    public function images(int $id, array $params = []): Collection
    {
        $data = $this->client->get("tv/{$id}/images", $params);

        return Image::collection(collect($data['backdrops'] ?? [])->concat($data['posters'] ?? []));
    }

    /**
     * @return Collection<int, Video>
     * @throws TmdbException
     */
    public function videos(int $id, array $params = []): Collection
    {
        return Video::collection($this->client->get("tv/{$id}/videos", $params)['results'] ?? []);
    }

    /**
     * @return Paginated<TvShow>
     * @throws TmdbException
     */
    public function recommendations(int $id, array $params = []): Paginated
    {
        return Paginated::of($this->client->get("tv/{$id}/recommendations", $params), TvShow::class);
    }

    /**
     * @return Paginated<TvShow>
     * @throws TmdbException
     */
    public function similar(int $id, array $params = []): Paginated
    {
        return Paginated::of($this->client->get("tv/{$id}/similar", $params), TvShow::class);
    }

    /**
     * @return array<string, mixed>
     * @throws TmdbException
     */
    public function externalIds(int $id): array
    {
        return $this->client->get("tv/{$id}/external_ids");
    }

    /**
     * @return array<string, mixed>
     * @throws TmdbException
     */
    public function watchProviders(int $id): array
    {
        return $this->client->get("tv/{$id}/watch/providers")['results'] ?? [];
    }

    /**
     * @throws TmdbException
     */
    public function latest(): TvShow
    {
        return TvShow::make($this->client->get('tv/latest'));
    }

    /**
     * @return Paginated<TvShow>
     * @throws TmdbException
     */
    public function airingToday(array $params = []): Paginated
    {
        return Paginated::of($this->client->get('tv/airing_today', $params), TvShow::class);
    }

    /**
     * @return Paginated<TvShow>
     * @throws TmdbException
     */
    public function onTheAir(array $params = []): Paginated
    {
        return Paginated::of($this->client->get('tv/on_the_air', $params), TvShow::class);
    }

    /**
     * @return Paginated<TvShow>
     * @throws TmdbException
     */
    public function popular(array $params = []): Paginated
    {
        return Paginated::of($this->client->get('tv/popular', $params), TvShow::class);
    }

    /**
     * @return Paginated<TvShow>
     * @throws TmdbException
     */
    public function topRated(array $params = []): Paginated
    {
        return Paginated::of($this->client->get('tv/top_rated', $params), TvShow::class);
    }
}
