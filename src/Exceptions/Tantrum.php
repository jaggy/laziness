<?php

namespace Work\Exceptions;

use Exception;

class Tantrum extends Exception
{
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
