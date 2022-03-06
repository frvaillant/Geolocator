<?php

namespace Francoisvaillant\Geolocator;

use Francoisvaillant\Geolocator\Providers\AbstractProvider;
use Francoisvaillant\Geolocator\Providers\AltitudeProvider;
use Francoisvaillant\Geolocator\Providers\DefaultProvider;

class Place
{

    /** @var string */
    private $address = null;

    /** @var string */
    private $streetName = null;

    /** @var int */
    private $zipCode = null;

    /** @var int */
    private $inseeCode = null;

    /** @var string */
    private $city = null;

    /** @var string */
    private $departmentCode = null;

    /** @var string */
    private $departmentName = null;

    /** @var string */
    private $regionName = null;

    /** @var float */
    private $latitude = null;

    /** @var int */
    private $lambertX = null;

    /** @var int */
    private $lambertY = null;

    /** @var float */
    private $longitude = null;

    /** @var int */
    private $altitude = null;

    /** @var AbstractProvider */
    private $provider;

    /** @var AltitudeProvider  */
    private $altitudeProvider;


    public function __construct($provider = DefaultProvider::class)
    {
        $this->provider = new $provider($this);

        $this->altitudeProvider = $altitudeProvider ?? new AltitudeProvider();
    }

    public function setAltitudeProvider(AltitudeProvider $altitudeProvider): self
    {
        $this->altitudeProvider = $altitudeProvider;

        return $this;
    }

    public function geolocate(): bool
    {
        if($this->getAddress() && ($this->getCity() || $this->getZipCode())) {
            return $this->provider->geolocate($this->getAddress(), $this->getCity(), $this->getZipCode());
        }
        return false;
    }

    public function reverse(): bool
    {
        if($this->getLatitude() && $this->getLongitude()) {
            return $this->provider->reverse($this->getLatitude(), $this->getLongitude());
        }
        return false;
    }


    /**
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    /**
     * @param string $streetName
     */
    public function setStreetName(?string $streetName): self
    {
        $this->streetName = $streetName;
        return $this;
    }


    /**
     * @return string|int
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param int $zipCode
     */
    public function setZipCode($zipCode): self
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getInseeCode(): ?int
    {
        return $this->inseeCode;
    }

    /**
     * @param int $inseeCode
     */
    public function setInseeCode(?int $inseeCode): self
    {
        $this->inseeCode = $inseeCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getDepartmentCode(): ?string
    {
        return $this->departmentCode;
    }

    /**
     * @param string $departmentCode
     */
    public function setDepartmentCode(?string $departmentCode): self
    {
        $this->departmentCode = $departmentCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getDepartmentName(): ?string
    {
        return $this->departmentName;
    }

    /**
     * @param string $departmentName
     */
    public function setDepartmentName(?string $departmentName): self
    {
        $this->departmentName = $departmentName;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegionName(): ?string
    {
        return $this->regionName;
    }

    /**
     * @param string $regionName
     */
    public function setRegionName(?string $regionName): self
    {
        $this->regionName = $regionName;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return int
     */
    public function findAltitude(): ?self
    {
        if ($this->latitude && $this->longitude) {
            $this->altitude = $this->altitudeProvider->findAltitude($this->latitude, $this->longitude);
            return $this;
        }
        return null;
    }

    public function getAltitude(): ?int
    {
        return $this->altitude;
    }


    /**
     * @return int
     */
    public function getLambertX(): ?int
    {
        return $this->lambertX;
    }

    /**
     * @param int $lambertX
     */
    public function setLambertX(?int $lambertX): self
    {
        $this->lambertX = $lambertX;
        return $this;
    }

    /**
     * @return int
     */
    public function getLambertY(): ?int
    {
        return $this->lambertY;
    }

    /**
     * @param int $lambertY
     */
    public function setLambertY(?int $lambertY): self
    {
        $this->lambertY = $lambertY;
        return $this;
    }

    /**
     * @return AbstractProvider
     */
    public function getProvider(): AbstractProvider
    {
        return $this->provider;
    }

    /** @return ApiGetter */
    public function getAltitudeProvider()
    {
        return $this->altitudeProvider;
    }

}