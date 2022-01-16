<?php

namespace Francoisvaillant\Geolocator\ResponseAnalyzers;

use Francoisvaillant\Geolocator\Place;

class DefaultProviderResponseAnalyzer implements ResponseAnalyzerInterface
{

    /** @var Place  */
    private $place;

    public function __construct(Place $place)
    {
        $this->place = $place;
    }

    public function formatData($data): ?array
    {
        if(!$data) {
            return null;
        }
        if(count($data['features']) === 1) {
            $data = array_merge($data['features'][0]['geometry']['coordinates'], $data['features'][0]['properties']);
        } else {
            $data = $this->checkForMatch($data);
        }

        if(!$data) {
            return null;
        }

        $context = $this->getContext($data['context'] ?? null);

        $placeInfos = [
            'city'       => $data['city'] ?? null,
            'zipCode'    => $data['postcode'] ?? null,
            'inseeCode'  => $data['citycode'] ?? null,
            'address'    => $data['name'] ?? null,
            'streetName' => $data['street'] ?? null,
            'latitude'   => $data[1] ?? null,
            'longitude'  => $data[0] ?? null,
            'lambertX'   => (int)$data['x'] ?? null,
            'lambertY'   => (int)$data['y'] ?? null,
        ];
        return array_merge($context, $placeInfos);
    }

    private function checkForMatch($data): ?array
    {
        $returnData = null;
        foreach ($data['features'] as $datum) {
            $placeCity = str_replace('-', ' ', strtoupper($this->place->getCity()));
            $city = str_replace('-', ' ', strtoupper($datum['properties']['city']));
            if ($city === $placeCity) {
                $returnData = array_merge($datum['geometry']['coordinates'], $datum['properties']);
            }
        }
        return $returnData;
    }

    /**
     * @param $context
     * @return array|null
     */
    private function getContext($context): array
    {
        if($context) {
            list($departmentCode, $departmentName, $regionName) = explode (', ', $context);
            return [
                'departmentCode' => $departmentCode,
                'departmentName' => $departmentName,
                'regionName'     => $regionName,
            ];
        }
        return [];
    }

}