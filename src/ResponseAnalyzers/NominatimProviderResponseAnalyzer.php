<?php

namespace Francoisvaillant\Geolocator\ResponseAnalyzers;

class NominatimProviderResponseAnalyzer implements ResponseAnalyzerInterface
{

    public function __construct()
    {

    }

    public function formatData($data): ?array
    {
        $datum = $data[0] ?? $data;
        if(
            isset($datum['address']) &&
            isset($datum['lat']) &&
            isset($datum['lon'])
        ) {
            return [
                'city'           => $datum['address']['city'] ?? $datum['address']['village'] ?? $datum['address']['town'] ?? $datum['address']['municipality'] ?? null,
                'zipCode'        => $datum['address']['postcode'] ?? null,
                'latitude'       => $datum['lat'] ?? null,
                'longitude'      => $datum['lon'] ?? null,
                'streetName'     => $datum['address']['road'] ?? null,
                'departmentName' => $datum['address']['county'] ?? null,
                'regionName'     => $datum['address']['state'] ?? null
            ];
        }

        return null;
    }

}