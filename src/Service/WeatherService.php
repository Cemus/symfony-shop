<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{
    public function __construct(private string $key, private readonly HttpClientInterface $httpClient)
    {
    }

    public function getKey(): string
    {
        return $this->key;
    }
    public function fetchWeather(): array
    {
        $url = 'https://api.openweathermap.org/data/2.5/weather?lon=1.44&lat=43.6&appid=' . $this->getKey();
        $response = $this->httpClient->request(
            'GET',
            $url
        );

        return $response->toArray();
    }
}