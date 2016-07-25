<?php

namespace Work\Http;

use GuzzleHttp\Client as Guzzle;

class Request
{
    /**
     * Guzzle HTTP client.
     *
     * @var Guzzle
     */
    protected $guzzle;

    /**
     * Create a new request.
     *
     * @param  Guzzle  $guzzle
     * @return Request
     */
    public function __construct(Guzzle $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * Send a request to the URL.
     *
     * Get the body response and convert it to an array.
     *
     * @param  string  $method
     * @param  string  $url
     * @param  mixed  $data
     * @return stdClass
     */
    public function send($method, $url, array $data = [])
    {
        $response = $this->guzzle->request($method, $url, $data);

        return $this->xmlToArray($response->getBody());
    }

    /**
     * Parse the xml string to an array.
     *
     * @param  string  $response
     * @return array
     */
    private function xmlToArray($response)
    {
        $xml  = simplexml_load_string($response);
        $json = json_encode($xml);

        return json_decode($json, $convert_to_array = true);
    }
}
