<?php


namespace App\Service;


abstract class AbstractCurlRequestInterface implements HttpRequestInterface, TemperatureInterface, CityInterface
{
    private $handle;

    public function init($url)
    {
        $this->handle = curl_init($url);
    }

    public function setOption(array $options)
    {
        foreach ($options as $key => $value) {
            curl_setopt($this->handle, constant($key), $value);
        }

        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
    }

    public function getInfo(string $name): string
    {
        return curl_getinfo($this->handle, $name);
    }

    public function error(): string
    {
        return curl_error($this->handle);
    }

    public function errno(): string
    {
        return curl_errno($this->handle);
    }

    public function execute(): string
    {
        return curl_exec($this->handle);
    }

    public function close()
    {
        if (is_resource($this->handle)) {
            curl_close($this->handle);
        }
    }
}