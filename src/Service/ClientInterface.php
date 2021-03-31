<?php


namespace App\Service;


interface ClientInterface
{
    public function call(string $city, string $country): array;
}