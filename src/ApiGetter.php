<?php

namespace Francoisvaillant\Geolocator;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiGetter
{
    const HEADERS   = [
        'Content-Type'  => 'application/x-www-form-urlencoded',
        'User-Agent' => 'Symfony HttpClient/Curl'
    ];

    /**
     * @var \Exception
     */
    protected ?\Exception $error = null;

    /**
     * @var HttpClientInterface
     */
    protected HttpClientInterface $client;



    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    public function getData($url)
    {
        try {
            $data = $this->request($url);
            if(isset($data['features']) && isset($data['features'][0])) {
                return array_merge($data['features'][0]['geometry']['coordinates'], $data['features'][0]['properties']);
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function request($url): ?array
    {
        $response = $this->client->request('GET', $url, [
            'headers' => self::HEADERS
        ]);
        if($response) {
            return json_decode($response->getContent(), true);
        }
        return null;
    }

}