<?php

namespace App\Tests\Unit\Service;

use App\Service\RouteCalculator;
use PHPUnit\Framework\TestCase;

class RouteCalculatorTest extends TestCase
{
    private RouteCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new RouteCalculator();
    }

    // --- Test 1 : mode "Loin" ---
    public function testOrderStopsFarPlacesTheFarthestStopLast(): void
    {
        // Arrange
        $departureLat = 48.8566; // Paris
        $departureLon = 2.3522;

        $stops = [
            ['name' => 'Marseille', 'lat' => 43.2965, 'lon' => 5.3698],  // ~660 km
            ['name' => 'Thoiry',    'lat' => 48.8833, 'lon' => 1.7667],  // ~42 km
            ['name' => 'Lyon',      'lat' => 45.7640, 'lon' => 4.8357],  // ~390 km
        ];

        // Act
        $ordered = $this->calculator->orderStopsFar($departureLat, $departureLon, $stops);

        // Assert
        $this->assertSame('Marseille', $ordered[count($ordered) - 1]['name']);
    }

    // --- Test 2 : mode "Proche" ---
    public function testOrderStopsNearPlacesTheNearestStopLast(): void
    {
        // Arrange
        $departureLat = 48.8566; // Paris
        $departureLon = 2.3522;

        $stops = [
            ['name' => 'Marseille', 'lat' => 43.2965, 'lon' => 5.3698],
            ['name' => 'Thoiry',    'lat' => 48.8833, 'lon' => 1.7667],
            ['name' => 'Lyon',      'lat' => 45.7640, 'lon' => 4.8357],
        ];

        // Act
        $ordered = $this->calculator->orderStopsNear($departureLat, $departureLon, $stops);

        // Assert
        $this->assertSame('Thoiry', $ordered[count($ordered) - 1]['name']);
    }

    // --- Test 3 : calcul de distance ---
    public function testCalculateDistanceReturnsApproximateKilometers(): void
    {
        // Arrange
        $calculator = new RouteCalculator();

        // Act — Paris → Marseille
        $distance = $calculator->calculateDistance(48.8566, 2.3522, 43.2965, 5.3698);

        // Assert — ~660 km, tolérance ±10 km
        $this->assertEqualsWithDelta(660, $distance, 10);
    }
}
