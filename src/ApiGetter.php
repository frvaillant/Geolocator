<?php

namespace Francoisvaillant\Geolocator;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

Abstract class ApiGetter
{
    const HEADERS   = [
        'Content-Type'  => 'application/x-www-form-urlencoded',
        'User-Agent' => 'Symfony HttpClient/Curl'
    ];

    /** @var string */
    protected $REVERSE_URL;

    /** @var string */
    protected $GEOCODE_URL;

    /** @var \Exception|null  */
    protected ?\Exception $error = null;

    /** @var HttpClientInterface  */
    protected HttpClientInterface $client;

    /** @var array | null */
    private $responseData = null;


    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    public function getData($url): ?array
    {
        try {
            $data = $this->request($url);
            return $data;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function makeGeocodeUrl(string $address, string $city, $zipCode): string
    {
        return sprintf($this->GEOCODE_URL, $address, $city, $zipCode);
    }

    protected function makeReverseUrl(float $latitude, float $longitude): string
    {
        return sprintf($this->REVERSE_URL, $latitude, $longitude);
    }


    protected function request($url): ?array
    {
        $response = $this->client->request('GET', $url, [
            'headers' => self::HEADERS
        ]);
        if($response) {
            $this->responseData = json_decode($response->getContent(), true);
            return $this->responseData;
        }
        return null;
    }

    /**
     * @return array|null
     */
    public function getResponseData(): ?array
    {
        return $this->responseData;
    }



}