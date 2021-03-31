<?php


namespace App\Service;


interface HttpRequestInterface
{
    public function init($url);

    public function setOption(array $options);

    public function getInfo(string $name): string;

    public function error(): string;

    public function errno(): string;

    public function execute(): string;

    public function close();
}