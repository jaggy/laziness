<?php

namespace Work\Traits;

trait TransformsKeys
{
    /**
     * Transform the keys from dashes/spaces to underscores.
     *
     * @param  array  $array
     * @return array
     */
    protected function arrayKeysToUnderscores(array $array)
    {
        $convert_dashes_to_underscores = function ($key) {
            return str_replace('-', '_', $key);
        };

        $values = array_values($array);
        $keys   = array_map($convert_dashes_to_underscores, array_keys($array));

        return array_combine($keys, $values);
    }
}
