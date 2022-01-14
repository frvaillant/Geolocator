<?php

namespace Francoisvaillant\Geolocator;


use mysql_xdevapi\TableInsert;

class Coordinator extends AbstractApiGetter
{

    /** @var array */
    protected $coordinates = null;

    /** @var float */
    protected $latitude = null;

    /** @var float */
    protected $longitude = null;

    /** @var string  */
    protected $geolocateUrl = 'https://api-adresse.data.gouv.fr/search/?q=%s&city=%s&postcode=%s';

    /** @var int */
    private $lambertX = null;

    /** @var int */
    private $lambertY = null;

    /**
     * @var ?array
     */
    private $data = null;


    public function __construct($address, $city, $postCode = null)
    {
        parent::__construct();
        $this->geoLocate($address, $city, $postCode);
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    private function geoLocate($address, $city, $postCode): void
    {
        $url = sprintf($this->geolocateUrl, urlencode($address), $city, $postCode);
        if($this->setData($url)) {
            $this->coordinates = $this->data['geometry']['coordinates'];
            $this->lambertX    = (int)$this->data['properties']['x'];
            $this->lambertY    = (int)$this->data['properties']['y'];
        }
    }

    /**
     * @param $url
     * @return array|null
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function setData($url): bool
    {
        try {
            $data = $this->request($url);
            if(isset($data['features']) && isset($data['features'][0])) {
                $this->data = $data['features'][0];
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->error = $e;
            return false;
        }
    }

    /**
     * @return float[]|null[]
     */
    public function getCoordinates(): array
    {
        return [
            'latitude'  => $this->getLatitude(),
            'longitude' => $this->getLongitude()
        ];
    }

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        if(!$this->coordinates) {
            return null;
        }
        return $this->coordinates[1];
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        if(!$this->coordinates) {
            return null;
        }
        return $this->coordinates[0];
    }

    /**
     * @return int
     */
    public function getLambertX(): int
    {
        return $this->lambertX;
    }

    /**
     * @return int
     */
    public function getLambertY(): int
    {
        return $this->lambertY;
    }

}