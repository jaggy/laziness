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
     * Cache the key value till 12am
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public static function put($key, $value)
    {
        $today = strtotime(date('Ymd'));
        $key   = "{$today}.{$key}";

        $minutes               = (int) date('i');
        $total_minutes_today   = (date('G') * 60) + $minutes;
        $minutes_till_midnight = 1440 - $total_minutes_today;

        static::$cache->put($key, $value, $minutes_till_midnight);
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
        if (in_array($method, ['put', 'has', 'get'])) {
            $today = strtotime(date('Ymd'));

            $args[0] =  $today . '.' . $args[0];
        }

        return call_user_func_array([static::$cache, $method], $args);
    }
}
