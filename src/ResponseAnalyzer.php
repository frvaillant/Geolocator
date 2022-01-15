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
            if (strtoupper($datum['properties']['city']) === strtoupper($this->place->getCity())) {
                return array_merge($datum['geometry']['coordinates'], $datum['properties']);
            }
        }
        return null;
    }

}