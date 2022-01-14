<?php

namespace Francoisvaillant\Geolocator;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractApiGetter
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

    /**
     * @param $url
     * @return array|null
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     *
     */
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

    /**
     * @return \Exception
     *
     * get Request Exceptions if needed
     */
    public function getError(): \Exception
    {
        return $this->error;
    }

}