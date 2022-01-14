<?php

namespace Francoisvaillant\Geolocator;

class Place
{

    /** @var array */
    private $coordinates = null;

    /** @var array */
    private $properties = null;

    /** @var string */
    private $address = null;

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

    /** @var string  */
    protected $geolocateUrl = 'https://api-adresse.data.gouv.fr/search/?q=%s&city=%s&postcode=%s';

    /** @var string  */
    protected $reverseUrl  = 'https://api-adresse.data.gouv.fr/reverse/?lon=%f&lat=%f';

    /** @var ApiGetter  */
    private $dataGetter;

    const TRANSLATOR = [
        0 => 'longitude',
        1 => 'latitude',
        'name' => 'address',
        'postcode' => 'zipCode',
        'citycode' => 'inseeCode',
        'city' => 'city',
        'x' => 'lambertX',
        'y' => 'lambertY',
        'context' => 'context'
    ];

    public function setContext($context): void
    {
        if($context) {
            list($departmentCode, $departmentName, $regionName) = explode (', ', $context);
            $this->setDepartmentCode($departmentCode);
            $this->setDepartmentName($departmentName);
            $this->setRegionName($regionName);
        }
    }

    public function __construct()
    {
        $this->dataGetter = new ApiGetter();
    }

    public function geolocate(): self
    {
        if ($this->address && $this->city) {
            $url  = sprintf($this->geolocateUrl, $this->address, $this->city, $this->zipCode);
            $data = $this->dataGetter->getData($url);
            if($data) {
                $this->hydrate($data);
            }
        }
        return $this;
    }

    public function reverse(): self
    {
        if ($this->latitude && $this->longitude) {
            $url  = sprintf($this->reverseUrl, $this->longitude, $this->latitude);
            $data = $this->dataGetter->getData($url);
            if($data) {
                $this->hydrate($data);
            }
        }
        return $this;
    }

    private function hydrate($data)
    {
        foreach (self::TRANSLATOR as $sourceKey => $targetKey) {
            $getter = 'set' . ucfirst($targetKey);
            $this->{$getter}($data[$sourceKey]);
        }
    }

    /**
     * @return array
     */
    public function getCoordinates(): ?array
    {
        return $this->coordinates;
    }

    /**
     * @param array $coordinates
     */
    public function setCoordinates(array $coordinates): self
    {
        $this->coordinates = $coordinates;
        return $this;
    }

    /**
     * @return array
     */
    public function getProperties(): ?array
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     */
    public function setProperties(array $properties): self
    {
        $this->properties = $properties;
        return $this;
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
    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return int
     */
    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    /**
     * @param int $zipCode
     */
    public function setZipCode(int $zipCode): self
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
    public function setInseeCode(int $inseeCode): self
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
    public function setCity(string $city): self
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
    public function setDepartmentCode(string $departmentCode): self
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
    public function setDepartmentName(string $departmentName): self
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
    public function setRegionName(string $regionName): self
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
    public function setLatitude(float $latitude): self
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
    public function setLongitude(float $longitude): self
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
            $altimeter = new Altimeter($this->latitude, $this->longitude);
            $this->altitude = $altimeter->getAltitude();
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
    public function setLambertX(int $lambertX): self
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
    public function setLambertY(int $lambertY): self
    {
        $this->lambertY = $lambertY;
        return $this;
    }



}