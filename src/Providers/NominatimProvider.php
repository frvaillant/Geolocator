<?php

namespace Francoisvaillant\Geolocator\Providers;

use Francoisvaillant\Geolocator\Place;
use Francoisvaillant\Geolocator\ResponseAnalyzers\NominatimProviderResponseAnalyzer;

class NominatimProvider extends AbstractProvider
{

    protected $REVERSE_URL = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=%s&lon=%s&extratags=1';

    protected $GEOCODE_URL = 'https://nominatim.openstreetmap.org/search?format=json&street=%s&city=%s&postalcode=%s&addressdetails=1&extratags=1';

    public function __construct(Place $place)
    {
        parent::__construct($place, new NominatimProviderResponseAnalyzer());
    }
}
