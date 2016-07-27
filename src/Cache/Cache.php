<?php

namespace Work\Cache;

use Illuminate\Cache\Repository as IlluminateCache;

class Cache
{
    const LOCATION = '/tmp/work';

    /**
     * Illuminate cache repository handler.
     *
     * @var IlluminateCache
     */
    protected static $cache;

    /**
     * Set the cache handler.
     *
     * @param  IlluminateCache  $cache
     * @return void
     */
    public static function setCacheRepository(IlluminateCache $cache)
    {
        static::$cache = $cache;
    }

    /**
     * Call the cache methods.
     *
     * @param  string  $method
     * @param  array  $args
     * @return mixed
     */
    public static function __callStatic($method, $args = [])
    {
        return call_user_func_array([static::$cache, $method], $args);
    }
}
