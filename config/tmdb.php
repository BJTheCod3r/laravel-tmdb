<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Read Access Token (v4 Bearer)
    |--------------------------------------------------------------------------
    |
    | The recommended authentication method. TMDB issues a long-lived "API
    | Read Access Token" under your account's API settings. When present it is
    | sent as an "Authorization: Bearer <token>" header on every request.
    |
    */

    'token' => env('TMDB_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | API Key (v3)
    |--------------------------------------------------------------------------
    |
    | The classic v3 API key. Used as a fallback when no read access token is
    | configured; it is appended to each request as an "api_key" query param.
    |
    */

    'api_key' => env('TMDB_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The root of the TMDB REST API. You should rarely need to change this.
    |
    */

    'base_url' => env('TMDB_BASE_URL', 'https://api.themoviedb.org/3'),

    /*
    |--------------------------------------------------------------------------
    | Default Language & Region
    |--------------------------------------------------------------------------
    |
    | Sent with every request as the "language" and "region" query params.
    | Language is an ISO 639-1 (optionally with country, e.g. "en-US"); region
    | is an ISO 3166-1 code used to localize release dates and providers.
    |
    */

    'language' => env('TMDB_LANGUAGE', 'en-US'),

    'region' => env('TMDB_REGION'),

    /*
    |--------------------------------------------------------------------------
    | Include Adult Content
    |--------------------------------------------------------------------------
    |
    | Default value for the "include_adult" parameter on search and discover
    | requests. Individual calls may override this per request.
    |
    */

    'include_adult' => env('TMDB_INCLUDE_ADULT', false),

    /*
    |--------------------------------------------------------------------------
    | HTTP Settings
    |--------------------------------------------------------------------------
    |
    | Request timeout (seconds), how many times to retry transient failures,
    | and the delay (milliseconds) between retries. Retries apply to TMDB
    | rate-limit (429) and 5xx responses.
    |
    */

    'timeout' => env('TMDB_TIMEOUT', 10),

    'retry' => [
        'times' => env('TMDB_RETRY_TIMES', 2),
        'sleep' => env('TMDB_RETRY_SLEEP', 250),
    ],

];
