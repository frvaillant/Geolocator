<?php

namespace Francoisvaillant\Geolocator;

class ResponseAnalyzer
{
    /** @var array  */
    private $data;

    /** @var Place  */
    private $place;

    public function __construct(Place $place)
    {
        $this->place = $place;
    }

    public function filterData($data): ?array
    {
        if(!$data) {
            return null;
        }
        if(count($data['features']) === 1) {
            return array_merge($data['features'][0]['geometry']['coordinates'], $data['features'][0]['properties']);
        }
        foreach ($data['features'] as $datum) {
            $placeCity = str_replace('-', ' ', strtoupper($this->place->getCity()));
            $city      = str_replace('-', ' ', strtoupper($datum['properties']['city']));
            if ($city === $placeCity || $this->isSimilar($placeCity, $city)) {
                return array_merge($datum['geometry']['coordinates'], $datum['properties']);
            }
        }
        return null;
    }

    private function isSimilar($city1, $city2)
    {
        return similar_text($city1, $city2);
    }

}