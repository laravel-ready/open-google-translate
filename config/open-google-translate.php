<?php

return [
    /**
     * Enable or disable response caching.
     * This is useful for large responses that are not cached in the database.
     * If you are using the database cache, you can disable this.
     * 
     * Default: true
     */
    'use_cache' => true,

    /**
     * Cache expiration time in minutes.
     * 
     * Default: 60 * 24 * 7 (1 week)
     */
    'cache_minutes' => 60 * 24 * 7,

    /**
     * Sleep time in seconds between each request.
     * Google can set a limit of requests per second or minute.
     * So we may need to sleep for a while to avoid exceeding the limit.
     * 
     * When you set this value to 0, no sleep will be done.
     * 
     * Default: 0
     */
    'sleep_seconds' => 0,
];
