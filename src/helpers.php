<?php

if (! function_exists('tap')) {
    /**
     * Tap the object.
     *
     * @param  mixed  $object
     * @param  Callable  $callback
     * @return mixed
     */
    function tap($object, $callback)
    {
        $callback($object);

        return $object;
    }
}

if (! function_exists('array_random')) {
    /**
     * Pick a random value from the array.
     *
     * @param  array  $random
     * @return mixed
     */
    function array_random(array $array)
    {
        return $array[array_rand($array)];
    }
}
