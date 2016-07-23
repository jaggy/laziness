<?php

namespace Work\Basecamp;

use GuzzleHttp\Client as Guzzle;
use Work\Http\Request;

class Api
{
    /**
     * Create a new API handler.
     *
     * @return Api
     */
    public function __construct()
    {
        $this->request = new Request(new Guzzle);
    }

    /**
     * Send the request to the basecamp api.
     *
     * @param  string  $method
     * @param  string  $url
     * @param  data  $array
     * @return mixed
     */
    protected function request($method, $url, array $data = [])
    {
        $defaults = [
            'auth' => [getenv('BASECAMP_USERNAME'), getenv('BASECAMP_PASSWORD')]
        ];

        return $this->request->send(
            $method,
            getenv('BASECAMP_URL') . $url,
            $defaults
        );
    }
}
