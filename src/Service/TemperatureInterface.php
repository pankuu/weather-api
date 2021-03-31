<?php


namespace App\Service;


interface TemperatureInterface
{
    public function setTemp(string $data);

    public function getTemp(): string;
}