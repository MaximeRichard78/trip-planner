<?php

namespace App\Service;

class RouteCalculator
{
    /**
     * Calcule la distance en km entre deux points (formule de Haversine).
     */
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    /**
     * Trie les arrêts : le point final est le plus LOIN du départ.
     *
     * @param array<array{name: string, lat: float, lon: float}> $stops
     * @return array<array{name: string, lat: float, lon: float}>
     */
    public function orderStopsFar(float $depLat, float $depLon, array $stops): array
    {
        usort($stops, function ($a, $b) use ($depLat, $depLon) {
            $distA = $this->calculateDistance($depLat, $depLon, $a['lat'], $a['lon']);
            $distB = $this->calculateDistance($depLat, $depLon, $b['lat'], $b['lon']);
            return $distA <=> $distB; // croissant → le plus loin en dernier
        });

        return $stops;
    }

    /**
     * Trie les arrêts : le point final est le plus PROCHE du départ.
     *
     * @param array<array{name: string, lat: float, lon: float}> $stops
     * @return array<array{name: string, lat: float, lon: float}>
     */
    public function orderStopsNear(float $depLat, float $depLon, array $stops): array
    {
        usort($stops, function ($a, $b) use ($depLat, $depLon) {
            $distA = $this->calculateDistance($depLat, $depLon, $a['lat'], $a['lon']);
            $distB = $this->calculateDistance($depLat, $depLon, $b['lat'], $b['lon']);
            return $distB <=> $distA; // décroissant → le plus proche en dernier
        });

        return $stops;
    }
}