<?php

declare(strict_types=1);

namespace BjTheCod3r\Tmdb\Endpoints;

use BjTheCod3r\Tmdb\Exceptions\TmdbException;
use BjTheCod3r\Tmdb\Resources\Credits;
use BjTheCod3r\Tmdb\Resources\Image;
use BjTheCod3r\Tmdb\Resources\Movie;
use BjTheCod3r\Tmdb\Resources\Paginated;
use BjTheCod3r\Tmdb\Resources\Review;
use BjTheCod3r\Tmdb\Resources\Video;
use Illuminate\Support\Collection;

/**
 * The /movie endpoints.
 *
 * @see https://developer.themoviedb.org/reference/movie-details
 */
class Movies extends Endpoint
{
    /**
     * Get the primary information for a movie.
     *
     * @param  array<string, mixed>  $params  Extra query params, e.g.
     *                                         ['append_to_response' => ['credits', 'videos']].
     * @throws TmdbException
     */
    public function details(int $id, array $params = []): Movie
    {
        return Movie::make($this->client->get("movie/{$id}", $this->normalizeAppend($params)));
    }

    /**
     * Get the cast and crew for a movie.
     * @throws TmdbException
     */
    public function credits(int $id, array $params = []): Credits
    {
        return Credits::make($this->client->get("movie/{$id}/credits", $params));
    }

    /**
     * Get the images that belong to a movie.
     *
     * @return Collection<int, Image>
     * @throws TmdbException
     */
    public function images(int $id, array $params = []): Collection
    {
        $data = $this->client->get("movie/{$id}/images", $params);

        return Image::collection(array_merge($data['backdrops'] ?? [], $data['posters'] ?? []));
    }

    /**
     * Get the videos (trailers, teasers, clips) for a movie.
     *
     * @return Collection<int, Video>
     * @throws TmdbException
     */
    public function videos(int $id, array $params = []): Collection
    {
        return Video::collection($this->client->get("movie/{$id}/videos", $params)['results'] ?? []);
    }

    /**
     * Get user reviews for a movie.
     *
     * @return Paginated<Review>
     * @throws TmdbException
     */
    public function reviews(int $id, array $params = []): Paginated
    {
        return Paginated::of($this->client->get("movie/{$id}/reviews", $params), Review::class);
    }

    /**
     * Get movies recommended based on the given movie.
     *
     * @return Paginated<Movie>
     * @throws TmdbException
     */
    public function recommendations(int $id, array $params = []): Paginated
    {
        return Paginated::of($this->client->get("movie/{$id}/recommendations", $params), Movie::class);
    }

    /**
     * Get movies similar to the given movie.
     *
     * @return Paginated<Movie>
     * @throws TmdbException
     */
    public function similar(int $id, array $params = []): Paginated
    {
        return Paginated::of($this->client->get("movie/{$id}/similar", $params), Movie::class);
    }

    /**
     * Get the external IDs (IMDb, Wikidata, social handles) for a movie.
     *
     * @return array<string, mixed>
     * @throws TmdbException
     */
    public function externalIds(int $id): array
    {
        return $this->client->get("movie/{$id}/external_ids");
    }

    /**
     * Get the watch provider availability for a movie, keyed by region.
     *
     * @return array<string, mixed>
     * @throws TmdbException
     */
    public function watchProviders(int $id): array
    {
        return $this->client->get("movie/{$id}/watch/providers")['results'] ?? [];
    }

    /**
     * Get the most newly created movie (a single record).
     * @throws TmdbException
     */
    public function latest(): Movie
    {
        return Movie::make($this->client->get('movie/latest'));
    }

    /**
     * @return Paginated<Movie>
     * @throws TmdbException
     */
    public function nowPlaying(array $params = []): Paginated
    {
        return Paginated::of($this->client->get('movie/now_playing', $params), Movie::class);
    }

    /**
     * @return Paginated<Movie>
     * @throws TmdbException
     */
    public function popular(array $params = []): Paginated
    {
        return Paginated::of($this->client->get('movie/popular', $params), Movie::class);
    }

    /**
     * @return Paginated<Movie>
     * @throws TmdbException
     */
    public function topRated(array $params = []): Paginated
    {
        return Paginated::of($this->client->get('movie/top_rated', $params), Movie::class);
    }

    /**
     * @return Paginated<Movie>
     * @throws TmdbException
     */
    public function upcoming(array $params = []): Paginated
    {
        return Paginated::of($this->client->get('movie/upcoming', $params), Movie::class);
    }
}
