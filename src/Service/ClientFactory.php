<?php


namespace App\Service;


use App\Service\Clients\ClientOpenWeatherMap;
use App\Service\Clients\ClientWeatherStack;

class ClientFactory
{
    const OPEN_WEATHER = ClientOpenWeatherMap::NAME;
    const WEATHER_STACK = ClientWeatherStack::NAME;

    public static function factory(string $type, string $city, string $country): HttpRequestInterface
    {
        $client = null;

        switch ($type) {
            case self::OPEN_WEATHER:
                $client = new ClientOpenWeatherMap($city, $country);
                break;
            case self::WEATHER_STACK:
                $client = new ClientWeatherStack($city, $country);
                break;
            default:
                throw new \Exception('Could not recognize type');
        }

        return $client;
    }
}