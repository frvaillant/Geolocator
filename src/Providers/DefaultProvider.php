<?php

namespace Francoisvaillant\Geolocator\Providers;

use Francoisvaillant\Geolocator\Place;
use Francoisvaillant\Geolocator\ResponseAnalyzers\DefaultProviderResponseAnalyzer;

class DefaultProvider extends AbstractProvider
{

    protected $REVERSE_URL = 'https://api-adresse.data.gouv.fr/reverse/?lat=%f&lon=%f';

    protected $GEOCODE_URL = 'https://api-adresse.data.gouv.fr/search/?q=%s&city=%s&postcode=%s';

    public function __construct(Place $place)
    {
        parent::__construct($place, new DefaultProviderResponseAnalyzer($place));
    }
}
