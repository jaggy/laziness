<?php

namespace Work\Basecamp;

use SimpleXMLElement;
use GuzzleHttp\Client as Guzzle;
use Thirteen\Fetchable\FetchableProperties;
use Work\Http\Request;
use Work\Traits\TransformsKeys;

class Api
{
    use FetchableProperties, TransformsKeys;

    /**
     * Send the request to the basecamp api.
     *
     * @param  string  $method
     * @param  string  $url
     * @param  mixed  $array
     * @return mixed
     */
    protected function request($method, $url, $data = null)
    {
        $parameters = [
            'auth' => [getenv('BASECAMP_USERNAME'), getenv('BASECAMP_PASSWORD')],
            'body' => $data,
            'headers' => [
                'Accept'       => 'application/xml',
                'Content-Type' => 'application/xml',
            ]
        ];

        return (new Request(new Guzzle))->send(
            $method,
            getenv('BASECAMP_URL') . $url,
            $parameters
        );
    }

    /**
     * Transform the array items into objects and return a collection.
     *
     * @notes  I am not a piece of meat.
     *
     * @param  array  $collection
     * @param  string  $class
     * @return \Illuminate\Support\Collection
     */
    protected function objectify(array $collection, $class = null)
    {
        $class = $class ?: get_called_class();

        $transformToObject = function ($item) use ($class) {
            return new $class($this->arrayKeysToUnderscores($item));
        };

        return collect(array_map($transformToObject, $collection));
    }

    /**
     * Return an XML when the object is rendered as a string.
     *
     * @return string
     */
    public function __toString()
    {
        $xml = new SimpleXMLElement('<time-entry />');
        $attributes = array_flip($this->toArray());

        array_walk_recursive($attributes, [$xml, 'addChild']);

        $segments = explode("\n", $xml->asXml());

        return $segments[1];
    }
}
