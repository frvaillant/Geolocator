<?php

namespace Francoisvaillant\Geolocator;


use Symfony\Component\HttpClient\HttpClient;

class Geolocator
{

    private $latitude;
    private $longitude;
    const API_BASE_URL = 'https://api-adresse.data.gouv.fr/search/?q=';
    const HEADERS = [
        'Content-Type'  => 'application/x-www-form-urlencoded',
        'User-Agent' => 'Symfony HttpClient/Curl'
    ];

    public function __construct()
    {

    }

    private function isValidAddress($address)
    {
        return preg_match('/([0-9]+ [a-zA-ZÉÀÈ0-9\-_,\'âêûôîïëéèàçù])/', $address);
    }

    public function geoLocate($address, $zipCode, $city): ?array
    {
        $url = self::API_BASE_URL . urlencode($address . ', ' . $zipCode . ', ' . $city);

        return $this->request($url);
    }

    private function request($url): ?array
    {
        $client = HttpClient::create();

        $response = $client->request('GET', $url, [
            'headers' => self::HEADERS
        ]);

        $data = json_decode($response->getContent(), true);
        try {
            $coordinates = $data['features'][0]['geometry']['coordinates'];
        } catch (\Exception $e) {
            $coordinates = null;
        }
        return $coordinates;
    }

}