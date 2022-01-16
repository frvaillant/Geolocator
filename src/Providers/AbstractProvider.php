<?php

namespace Francoisvaillant\Geolocator\Providers;

use Francoisvaillant\Geolocator\ApiGetter;
use Francoisvaillant\Geolocator\Place;
use Francoisvaillant\Geolocator\ResponseAnalyzers\ResponseAnalyzerInterface;

abstract class AbstractProvider extends ApiGetter
{

    /** @var Place */
    private $place;

    /** @var ResponseAnalyzerInterface */
    private $analyzer;

    public function __construct(Place $place, ResponseAnalyzerInterface $analyzer)
    {
        $this->place    = $place;
        $this->analyzer = $analyzer;
        parent::__construct();
    }

    public function geolocate(string $address, string $city, $zipCode): bool
    {
        $data = $this->getData($this->makeGeocodeUrl($address, $city, $zipCode));
        return $this->hydrate($this->analyzer->formatData($data));
    }

    public function reverse(float $latitude, float $longitude): bool
    {
        $data = $this->getData($this->makeReverseUrl($latitude, $longitude));
        return $this->hydrate($this->analyzer->formatData($data));
    }

    public function hydrate($data): bool
    {
        if($data) {
            foreach ($data as $name => $value) {
                $getter = 'set' . ucfirst($name);
                $this->place->{$getter}($value);
            }
            return true;
        }
        return false;
    }

}