<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class BanApiClient
{
    public function __construct(private HttpClientInterface $httpClient) {}

    /**
     * Géocode une adresse française via l'API BAN.
     * Retourne ['lat' => float, 'lon' => float, 'label' => string] ou null.
     *
     * @return array{lat: float, lon: float, label: string}|null
     */
    public function geocode(string $address): ?array
    {
        $response = $this->httpClient->request('GET', 'https://api-adresse.data.gouv.fr/search/', [
            'query' => ['q' => $address, 'limit' => 1],
        ]);

        $data = $response->toArray(false);

        if (empty($data['features'])) {
            return null;
        }

        $feature = $data['features'][0];

        return [
            'lat'   => $feature['geometry']['coordinates'][1],
            'lon'   => $feature['geometry']['coordinates'][0],
            'label' => $feature['properties']['label'],
        ];
    }
}