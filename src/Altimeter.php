<?php


namespace Francoisvaillant\Geolocator;


use Symfony\Component\HttpClient\HttpClient;

class Altimeter extends ApiGetter
{

    /**
     * @var string
     *
     * all documentation here : https://www.opentopodata.org/
     *
     */

    protected $url = 'https://api.opentopodata.org/v1/test-dataset?locations=%s,%s';

    /** @var float */
    protected $latitude = null;

    /** @var float */
    protected $longitude = null;

    /** @var integer */
    protected $altitude  = null;


    public function __construct(float $latitude, float $longitude)
    {
        if(!$latitude || !$longitude) {
            throw new \Exception('You have to set latitude and longitude in order to use Altimeter');
        }
        $this->latitude  = $latitude;
        $this->longitude = $longitude;

        parent::__construct();
    }

    /**
     * @param string $coords
     * @return mixed
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function findAltitude(): ?int
    {
        if(!$this->latitude || !$this->longitude) {
            return null;
        }

        $result = $this->request(sprintf($this->url, $this->latitude, $this->longitude));
        if ($result && $result['status'] === 'OK') {
            $this->altitude = (int)$result['results'][0]['elevation'];
            return $this->altitude;
        }
        return null;
    }

    /**
     * @return int
     */
    public function getAltitude(): ?int
    {
        $this->findAltitude();
        return $this->altitude;
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
     * @param float $longitude
     */
    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }



}
