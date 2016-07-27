<?php

namespace Work\Basecamp;

use Work\Cache\Cache;

class Project extends Api
{
    /**
     * Calculate the remaining hours.
     *
     * @return float
     */
    public function remainingHours()
    {
        if (! $logged = Cache::get('time:remaining')) {
            Cache::put('time:remaining', $logged = $this->entries()->pluck('hours')->sum());
        }

        return TimeEntry::RENDERABLE_HOURS - $logged;
    }

    /**
     * Fetch all the times from the given project id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function entries()
    {
        $me = (new Person)->me();

        if ($entries = Cache::get('entries')) {
            return $entries;
        }

        $entries = $this->objectify(
            $this->request('GET', "/projects/{$this->id}/time_entries.xml")['time-entry'],
            TimeEntry::class
        );

        $entries = $entries
            ->where('person_id', $me->id)
            ->where('date', $today = date('Y-m-d'))
            ->values();

        Cache::put('entries', $entries);

        return $entries;
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

        Cache::put('time:remaining', $hours + Cache::get('time:remaining'));
    }

    /**
     * List out all the projects.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        if ($projects = Cache::get('projects')) {
            return $projects;
        }

        $projects = $this->objectify(
            $this->request('GET', '/projects.xml')['project']
        );

        Cache::put('projects', $projects);

        return $projects;
    }
}
