<?php

namespace Work\Basecamp;

class Project extends Api
{
    /**
     * Fetch all the times from the given project id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function entries()
    {
        $me = (new Person)->me();

        $entries = $this->objectify(
            $this->request('GET', "/projects/{$this->id}/time_entries.xml")['time-entry'],
            TimeEntry::class
        );

        return $entries
            ->where('person_id', $me->id)
            ->where('date', $today = date('Y-m-d'))
            ->values();
    }

    /**
     * List out all the projects.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->objectify(
            $this->request('GET', '/projects.xml')['project']
        );
    }
}
