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
     * @return void
     */
    public function send($method, $url, array $data = [])
    {
        $response = $this->guzzle->request($method, $url, $data);

        return $this->transformKeys($this->xmlToArray($response->getBody()));
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

    /**
     * Convert the array keys from dashes to underscores.
     *
     * @param  array  $array
     * @return array
     */
    private function transformKeys(array $array)
    {
        $convert_dashes_to_underscores = function ($key) {
            return str_replace('-', '_', $key);
        };

        $values = array_values($array);
        $keys   = array_map($convert_dashes_to_underscores, array_keys($array));

        return array_combine($keys, $values);
    }
}
