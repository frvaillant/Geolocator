# Geolocator

Geolocator is a simple class using open data API
https://adresse.data.gouv.fr/api-doc/adresse

## Setup
```
composer require francoisvaillant/geolocator
```

## Get Coordinates from address

```PHP
$coordinator = new francoisvaillant\geolocator\Coordinator('116 avenue du Président Kennedy','PARIS', 75016);
$coordinates = $coordinator->getCoordinates();
/*
this returns an array like
    [
        "latitude"  => 48.852149,
        "longitude" => 2.279529,
    ]
 */
```

## Get address from coordinates
```PHP
$PlaceInformator = new francoisvaillant\geolocator\PlaceInformator([2.279529, 48.852149]);
$placeInformations = $PlaceInformator->getCityInfos();
/*
this returns an array like
    [
    "address" => "116 Avenue du Président Kennedy",
    "postCode" => "75016",
    "city" => "Paris",
    "inseeCode" => "75116",
    "departmentCode" => "75",
    "departmentName" => "Paris",
    "regionName" => "Île-de-France",
    ]
 */
```

## Get altitude from coordinates
uses OpenTopoData APi. See https://www.opentopodata.org/ for details and restrictions
```PHP
    $altimeter = new \Francoisvaillant\Geolocator\Altimeter(44.22, -0.59);
    $altimeter->getAltitude();
/*
 This returns an integer for altitude in meters or null (example returns 98m)
 */
```