<?php

namespace Work\Exceptions;

use Exception;

class Table extends Exception
{
    /**
     * Generic exception.
     *
     * @param  string  $message
     * @return Tantrum
     */
    public static function out($message)
    {
        return new static($message);
    }
}
