<?php


namespace App\Service;


use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;


class Client implements ClientInterface
{
    const CLIENTS = [
        ClientBuilder::WEATHER_STACK,
        ClientBuilder::OPEN_WEATHER,
    ];

    public function call(string $city, string $country): array
    {
        try {
            $cachePool = new FilesystemAdapter('', 3600, "cache",);
            $tempCache = $cachePool->getItem($city);

            if (!$tempCache->isHit()) {
                $clientBuilder = new ClientBuilder();
                $temp = [];

                foreach (self::CLIENTS as $client) {
                    $clientBuilder->setType($client);
                    $clientBuilder->setCity($city);
                    $clientBuilder->setCountry($country);
                    $temp[] = $clientBuilder->build();
                }

                $tempCache->set($temp);
                $tempCache->expiresAfter(3600);
                $cachePool->save($tempCache);
            }

            if ($cachePool->hasItem($city)) {
                $cache = $cachePool->getItem($city);
                $temp = $cache->get('value');
            }

            return $temp;
        } catch (\Exception $e) {
            throw new \Exception($e);
        } catch (InvalidArgumentException $e) {
            throw new \InvalidArgumentException($e);
        }
    }
}