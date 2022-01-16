<?php


namespace Francoisvaillant\Geolocator\Providers;


use Francoisvaillant\Geolocator\ApiGetter;

class AltitudeProvider extends ApiGetter
{

    /**
     * @var string
     *
     * all documentation here : https://www.opentopodata.org/
     *
     */

    protected $url = 'https://api.opentopodata.org/v1/srtm30m?locations=%s,%s';

    /** @var integer */
    protected $altitude  = null;

    /**
     * @param string | null $url
     */
    public function __construct(string $url = null)
    {
        parent::__construct();

        if($url) {
            $this->url = $url;
        }
    }

    /**
     * @param string $coords
     * @return mixed
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function findAltitude(float $latitude, float $longitude): ?int
    {

        if(!$latitude || !$longitude) {
            return null;
        }

        $result = $this->request(sprintf($this->url, $latitude, $longitude));
        if ($result && $result['status'] === 'OK') {
            $this->altitude = (int)$result['results'][0]['elevation'];
            return $this->altitude;
        }
        return null;
    }

    /**
     * @param $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }



}
