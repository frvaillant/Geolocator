<?php

namespace Francoisvaillant\Geolocator\Providers;

use Francoisvaillant\Geolocator\ApiGetter;
use Francoisvaillant\Geolocator\ResponseAnalyzer;

class NominatimProvider extends ApiGetter
{

    const REVERSE_URL = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=%s&lon=%s';

    const GEOCODE_URL = 'https://nominatim.openstreetmap.org/search?format=json&street=%s&city=%s&postalcode=%s&addressdetails=1';

    public function __construct()
    {
        parent::__construct();
    }

    public function geolocate(string $address, string $city, $zipCode)
    {
        $url = sprintf(self::GEOCODE_URL, $address, $city, $zipCode);
        $data = $this->getData($url);
        return $this->formatData($data);
    }

    public function reverse(float $latitude, float $longitude)
    {
        $url = sprintf(self::REVERSE_URL, $latitude, $longitude);
        $data = $this->getData($url);
        return $this->formatData($data);
    }

    protected function formatData($data)
    {
        $datum = $data[0] ?? $data;
        if(
            isset($datum['address']) &&
            isset($datum['lat']) &&
            isset($datum['lon'])
        ) {
            return [
                'city'           => $datum['address']['city'] ?? $datum['address']['municipality'] ?? null,
                'zipCode'        => $datum['address']['postcode'] ?? null,
                'latitude'       => $datum['lat'] ?? null,
                'longitude'      => $datum['lon'] ?? null,
                'streetName'     => $datum['address']['road'] ?? null,
                'departmentName' => $datum['address']['county'] ?? null,
                'regionName'     => $datum['address']['state'] ?? null
            ];
        }
    }

}