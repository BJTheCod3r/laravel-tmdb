# Changelog

All notable changes to `bjthecod3r/laravel-tmdb` will be documented in this file.

## v0.1.0 - Unreleased

Initial release.

- Fluent `Tmdb` facade over the TMDB v3 REST API.
- Bearer (v4 read access token) authentication, with v3 `api_key` fallback.
- Typed resource DTOs: `Movie`, `TvShow`, `Season`, `Episode`, `Person`,
  `Genre`, `Credits`, `Image`, `Video`, `Review`, `Keyword`, `MovieCollection`
  and a `Paginated` wrapper.
- Endpoint groups: `movies`, `tv`, `people`, `search`, `discover`, `trending`,
  `genres`, `configuration`.
- Fluent `discover()` query builder.
- Configurable `include_adult` default applied to search and discover requests.
- `image()` helper for building absolute TMDB image URLs (configurable CDN root).
- Typed exceptions for auth, not-found, validation and rate-limit failures,
  with automatic retry of GET requests on transient errors (honouring
  `Retry-After`).
- Support for Laravel 11, 12 and 13 on PHP 8.2+.
