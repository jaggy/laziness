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
