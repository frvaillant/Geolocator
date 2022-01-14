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
    ->setZipCode(00000); // optionnal but recommended
    ->geolocate();

$latitude  = $place->getLatitude();
$longitude = $place->getLongitude();

```

## Get address from coordinates
```PHP
$place
    ->setLatitude(45.548);
    ->setLongitude(1.897);
    ->reverse();

$address = $place->getAddress();
$zipCode = $place->getZipCode();
$city    = $place->getCity();

```

## Get altitude from coordinates
uses OpenTopoData APi. See https://www.opentopodata.org/ for details and restrictions
```PHP
$place
    ->setLatitude(45.548);
    ->setLongitude(1.897);
    ->findAltitude()
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

