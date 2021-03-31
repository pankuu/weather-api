<?php


namespace App\Service;


class ClientBuilder
{
    const OPEN_WEATHER = ClientFactory::OPEN_WEATHER;
    const WEATHER_STACK = ClientFactory::WEATHER_STACK;

    private $type;
    private $city;
    private $country;

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return HttpRequestInterface
     * @throws \Exception
     */
    public function build(): HttpRequestInterface
    {
        $client = null;

        switch ($this->type) {
            case self::OPEN_WEATHER:
            case self::WEATHER_STACK:
                $client = ClientFactory::factory($this->type, $this->city, $this->country);
                break;
            default:
                throw new \Exception('Could not recognize type');
        }

        return $client;
    }
}