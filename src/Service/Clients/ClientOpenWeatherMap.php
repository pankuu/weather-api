<?php


namespace App\Service\Clients;


use App\Service\AbstractCurlRequestInterface;

class ClientOpenWeatherMap extends AbstractCurlRequestInterface
{
    const NAME = 'open_weather';

    private string $city;
    private string $country;
    private float $temp;
    private string $apiKey;
    private string $host;

    public function __construct(string $city, string $country)
    {
        $this->city = $city;
        $this->country = $country;
        $this->temp = 0;
        $this->apiKey = $_SERVER['OPEN_WEATHER_API_KEY'];
        $this->host = $_SERVER['OPEN_WEATHER_HOST'];
        $this->call();
    }

    /**
     * @param string $temp
     */
    public function setTemp(string $temp): void
    {
        $json = json_decode($temp, true);

        $this->temp = $json['main']['temp'];
    }

    /**
     * @return string
     */
    public function getTemp(): string
    {
        return $this->temp;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function call(): string
    {
        try {
            $url = "{$this->host}?q={$this->city},{$this->country}&APPID={$this->apiKey}&units=metric";

            $this->init($url);
            $options = [
                "CURLOPT_RETURNTRANSFER" => true,
                "CURLOPT_FOLLOWLOCATION" => true,
                "CURLOPT_MAXREDIRS" => 10,
                "CURLOPT_TIMEOUT" => 30,
                "CURLOPT_HTTP_VERSION" => CURL_HTTP_VERSION_1_1,
                "CURLOPT_CUSTOMREQUEST" => "GET",
            ];
            $this->setOption($options);
            $response = $this->execute();
            $error = $this->error();
            $errno = $this->errno();
            $this->close();

            if ($error) {
                throw new \Exception("cURL Error #: {$error} {$errno} ");
            }

            $this->setTemp($response);

            return $this->getTemp();
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}