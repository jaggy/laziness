<?php

namespace Work\Basecamp;

class Project extends Api
{
    /**
     * List out all the projects.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->objectify($this->request('GET', '/projects.xml')['project']);
    }

    /**
     * Transform the array items into objects and return a collection.
     *
     * @param  array  $collection
     * @return \Illuminate\Support\Collection
     */
    private function objectify(array $collection)
    {
        $transformToObject = function ($item) { return (object) $item; };

        return collect(array_map($transformToObject, $collection));
    }
}
