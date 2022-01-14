<?php

namespace Francoisvaillant\Geolocator;

class PlaceInformator extends AbstractApiGetter
{
    /** @var string  */
    protected $reverseUrl  = 'https://api-adresse.data.gouv.fr/reverse/?lon=%f&lat=%f';

    /** @var array */
    protected $coordinates = null;
    protected $cityInfos   = null;

    /**
     * @param array $coordinates
     * [float longitude, float latitude]
     */
    public function __construct(array $coordinates)
    {
        parent::__construct();

        $this->coordinates = $coordinates;
    }

    protected function searchForCityInfo()
    {
        if(!$this->coordinates) {
            return false;
        }
        $url = sprintf($this->reverseUrl, $this->coordinates[0], $this->coordinates[1]);
        try {
            $this->cityInfos = $this->request($url)['features'][0]['properties'];
            return true;
        } catch (\Exception $e) {
            $this->error = $e;
            return false;
        }
    }

    public function getCityInfos(): ?array
    {
        if($this->searchForCityInfo()) {
            if ($context = $this->getContext()) {
                $cityInfos = [
                    'address' => $this->getAddress(),
                    'postCode' => $this->getZipCode(),
                    'city' => $this->getCity(),
                    'inseeCode' => $this->getInseeCode()
                ];

                return array_merge($cityInfos, $context);
            }
        }
        return null;
    }

    public function getAddress(): ?string
    {
        return $this->cityInfos['name'] ?? null;
    }


    public function getZipCode(): ?string
    {
        return $this->cityInfos['postcode'] ?? null;
    }

    public function getInseeCode(): ?string
    {
        return $this->cityInfos['citycode'] ?? null;
    }

    public function getCity(): ?string
    {
        return $this->cityInfos['city'] ?? null;
    }

    public function getContext(): ?array
    {
        if($this->cityInfos['context']) {
            try {
                list ($departmentCode, $departmentName, $regionName) = explode(', ', $this->cityInfos['context']);
                return [
                    'departmentCode' => $departmentCode,
                    'departmentName' => $departmentName,
                    'regionName' => $regionName
                ];
            } catch (\Exception $e) {
                $this->error = $e;
                return null;
            }
        }
        return null;
    }

    public function getDepartmentCode(): ?string
    {
        return $this->getContext()['departmentCode'] ?? null;
    }

    public function getDepartmentName(): ?string
    {
        return $this->getContext()['departmentName'] ?? null;

    }

    public function getRegionName(): ?string
    {
        return $this->getContext()['regionName'] ?? null;

    }

}