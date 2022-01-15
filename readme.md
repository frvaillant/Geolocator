# Geolocator

Geolocator is a simple class using open data API
https://adresse.data.gouv.fr/api-doc/adresse

## Setup
```
composer require francoisvaillant/geolocator
```

## All begin by a place
```PHP
$place = new \Francoisvaillant\Geolocator\Place()
```

## Get Coordinates from address

```PHP
$place
    ->setAddress('your address');
    ->setCity('city name');
    ->setZipCode(00000); // optionnal
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
    ->findAltitude() // NULL if failed
    ->getAltitude();

```

## What you can get :
Once you hydrate totaly your Place whith ->geolocate() or ->reverse() (alltitude is a bit different),
you'll be able to get all the above informations :

    - city
    - zipCode
    - address
    - inseeCode
    - department code
    - departement name
    - region name
    - latitude and longitude (degrees)
    - latitude and longitude (lambert93)

