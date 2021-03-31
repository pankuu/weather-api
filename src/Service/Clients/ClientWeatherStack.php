<?php


namespace App\Service\Clients;


use App\Service\AbstractCurlRequestInterface;

class ClientWeatherStack extends AbstractCurlRequestInterface
{
    const NAME = 'weather_stack';

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
        $this->apiKey = $_SERVER['WEATHER_STACK_KEY'];
        $this->host = $_SERVER['WEATHER_STACK_HOST'];
        $this->call();
    }

    /**
     * @param string $temp
     */
    public function setTemp(string $temp): void
    {
        $json = json_decode($temp, true);

        $this->temp = $json['current']['temperature'];
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
            $queryString = http_build_query([
                'access_key' => $this->apiKey,
                'query' => $this->city
            ]);

            $url = "{$this->host}?{$queryString},{$this->country}";

            $this->init($url);
            $options = [
                "CURLOPT_RETURNTRANSFER" => true,
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