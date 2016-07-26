<?php

namespace Work\Basecamp;

class Project extends Api
{
    /**
     * Calculate the remaining hours.
     *
     * @return float
     */
    public function remainingHours()
    {
        return TimeEntry::RENDERABLE_HOURS - $this->entries()->pluck('hours')->sum();
    }

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
     * Log hours to the basecamp api.
     *
     * @param  string  $description
     * @param  float  $hours
     * @return void
     */
    public function log($description, $hours)
    {
        $me = (new Person)->me();

        $entry = new TimeEntry([
            'person-id'   => $me->id,
            'date'        => date('Y-m-d'),
            'hours'       => (string) $hours,
            'description' => $description,
        ]);

        $this->request(
            'POST',
            "/projects/{$this->id}/time_entries.xml",
            (string) $entry
        );
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
