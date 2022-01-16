# Geolocator

Geolocator is a simple tool to geolocate address or reverse. It is also capable to 
determinate altitude.

It uses 3 Api :
- https://adresse.data.gouv.fr/api-doc/adresse
- https://nominatim.org/release-docs/develop/
- https://www.opentopodata.org/

## Setup
```
composer require francoisvaillant/geolocator
```

## namespaces
- \Francoisvaillant\Geolocator\
- \Francoisvaillant\Geolocator\Providers

## All begin by a place
```PHP
$place = new Place()
```

By default, we uses french Government "adresses Api". If you prefer (especially out of France), 
you can use NominatimApi by setting it as provider for your Place :
```PHP
$place = new Place(NominatimProvider::class)
```

## Get Coordinates from address
In order to geolocate an address, your have first to set it and setting City or ZipCode.
City is often not enough. It's highly recommended to set city and zipCode before using geolocation.
```PHP
$place
    ->setAddress('your address');
    ->setCity('city name');
    ->setZipCode(00000);
    ->geolocate();

$latitude  = $place->getLatitude();  // NULL if geolocation failed
$longitude = $place->getLongitude(); // NULL if geolocation failed
```

## Get address from coordinates
```PHP
$place
    ->setLatitude(45.548);
    ->setLongitude(1.897);
    ->reverse();

$address = $place->getAddress(); // NULL if reverse failed
$zipCode = $place->getZipCode(); // NULL if reverse failed
$city    = $place->getCity();    // NULL if reverse failed
```

## Get altitude from coordinates
uses OpenTopoData APi. See https://www.opentopodata.org/ for details and restrictions
```PHP
$place
    ->setLatitude(45.548);
    ->setLongitude(1.897);
    ->findAltitude() 
    ->getAltitude(); // NULL if failed
```

If you host your own instance of OpenTopoData Api, please change AltitudeProvider :
```PHP
    $altitudeProvider = new AltitudeProvider('http://localhost:5000/v1/srtm30m?locations=%s,%s'); // note that first %s is for latitude, second one is for longitude
    $place->setAltitudeProvider($altitudeProvider);
```

## What you can get :
Once you totally hydrated your Place whith ->geolocate() or ->reverse() (altitude is a bit different),
you'll be able to get all the above informations (However depends on the provider) :

    - city
    - zipCode
    - address
    - inseeCode
    - department code
    - departement name
    - region name
    - latitude and longitude (degrees)
    - latitude and longitude (lambert93)

Note that the geolocate() and reverse() return true or false if succeed or not. So you can use :
```PHP
    if($place->geolocate()) {
        // ...
    }

    if($place->reverse()) {
        // ...
    }
```