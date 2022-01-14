<?php

namespace Francoisvaillant\Geolocator;


use Symfony\Component\HttpClient\HttpClient;
use \Symfony\Contracts\HttpClient\HttpClientInterface;

class Geolocator extends AbstractApiGetter
{
    private float $latitude;
    private float $longitude;
    private $apiUrl = 'https://api-adresse.data.gouv.fr/search/?q=%s';

    /**
     * @var array|null
     */
    private $coordinates;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public function geoLocate($address): self
    {
        $url = sprintf($this->apiUrl, urlencode($address));
        $this->coordinates = $this->getCoordinatesInResponse($url);
        return $this;
    }

    /**
     * @param $url
     * @return array|null
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function getCoordinatesInResponse($url): ?array
    {
        $coordinates = null;

        try {
            $data = $this->request($this->apiUrl);
            $coordinates = $data['features'][0]['geometry']['coordinates'];
        } catch (\Exception $e) {
            $this->error = $e;
        }

        return $coordinates;
    }

    /**
     * @return bool
     */
    public function hasCoordinates(): bool
    {
        return $this->coordinates !== null;
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
        if(!$this->hasCoordinates()) {
            return null;
        }
        return $this->coordinates[1];
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        if(!$this->hasCoordinates()) {
            return null;
        }
        return $this->coordinates[0];
    }

}