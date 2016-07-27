<?php

namespace Work\Exceptions;

use Exception;

class Tantrum extends Exception
{
    /**
     * Generic tantrum.
     *
     * @return Tantrum
     */
    public static function table($message)
    {
        return new static($message);
    }

    /**
     * Don't work overtime!
     *
     * @return Tantrum
     */
    public static function overtime()
    {
        return new static('WHY ARE YOU WORKING OUTSIDE?! (╯°□°）╯︵ ┻━┻');
    }
}
