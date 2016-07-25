<?php

namespace Work\Basecamp;

class Person extends Api
{
    /**
     * Return the logged user's data.
     *
     * @return Person
     */
    public function me()
    {
        return new static($this->arrayKeysToUnderscores(
            $this->request('GET', '/me.xml')
        ));
    }
}
