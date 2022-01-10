<?php

namespace Francoisvaillant\Geolocator;


use Symfony\Component\HttpClient\HttpClient;
use \Symfony\Contracts\HttpClient\HttpClientInterface;

class Geolocator
{
    private float $latitude;
    private float $longitude;
    private $apiUrl = 'https://api-adresse.data.gouv.fr/search/?q=%s';
    const HEADERS   = [
        'Content-Type'  => 'application/x-www-form-urlencoded',
        'User-Agent' => 'Symfony HttpClient/Curl'
    ];

    /**
     * @var array|null
     */
    private $coordinates;

    /**
     * @var \Exception
     */
    private ?\Exception $error = null;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    public function __construct($apiUrl = null)
    {
        $this->client = HttpClient::create();

        if($apiUrl) {
            $this->apiUrl = $apiUrl;
        }
    }

    public function geoLocate($address): self
    {
        $url = sprintf($this->apiUrl, urlencode($address));
        $this->coordinates = $this->request($url);
        return $this;
    }

    /**
     * @param $url
     * @return array|null
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     *
     * Get coordinates from address using Open Data French Government Api
     */
    private function request($url): ?array
    {
        $coordinates = null;

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => self::HEADERS
            ]);

            $data = json_decode($response->getContent(), true);

            $coordinates = $data['features'][0]['geometry']['coordinates'];
        } catch (\Exception $e) {
            $this->error = $e;
        }

        return $coordinates;
    }

    /**
     * @return \Exception
     *
     * get Request Exceptions if needed
     */
    public function getError(): \Exception
    {
        return $this->error;
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