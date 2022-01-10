#Geolocator

Geolocator is a simple class using opend data API
https://adresse.data.gouv.fr/api-doc/adresse

##Setup
```
composer require francoisvaillant/geolocator
```

##Instantiate class
```PHP
$geolocator = new \Francoisvaillant\Geolocator\Geolocator();
```

##Geolocate address
```PHP
$geolocator  = new \Francoisvaillant\Geolocator\Geolocator();
$address     = '255 rue Saint-Catherine, 33000 Bordeaux';
$coordinates = $geolocator->geoLocate($address)->getCoordinates();
/**
* returns ['latitude' => 44.832251, 'longitude' => -0.572988]
*/
```

##Get address from coordinates
On the way