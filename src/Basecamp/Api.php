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
     * @param  arrawy  $data
     * @return mixed
     */
    protected function request(string $method, string $url, array $data = [])
    {
        return $this->request->send(
            $method,
            getenv('BASECAMP_URL') . $url,
            $data
        );
    }
}
