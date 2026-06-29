<?php

namespace App\Tests\Unit\Service;

use App\Service\BanApiClient;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class BanApiClientTest extends TestCase
{
    public function testGeocodeReturnsCoordinatesOnSuccess(): void
    {
        // Arrange
        $fakeApiResponse = [
            'features' => [
                [
                    'geometry'   => ['coordinates' => [2.3522, 48.8566]],
                    'properties' => ['label' => '75000 Paris'],
                ],
            ],
        ];

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->expects($this->once())
            ->method('toArray')
            ->with(false)
            ->willReturn($fakeApiResponse);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->expects($this->once())
            ->method('request')
            ->willReturn($mockResponse);

        $client = new BanApiClient($mockHttpClient);

        // Act
        $result = $client->geocode('Paris');

        // Assert
        $this->assertNotNull($result);
        $this->assertEqualsWithDelta(48.8566, $result['lat'], 0.0001);
        $this->assertEqualsWithDelta(2.3522,  $result['lon'], 0.0001);
        $this->assertSame('75000 Paris', $result['label']);
    }

    public function testGeocodeReturnsNullWhenNoFeatures(): void
    {
        // Arrange
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->expects($this->once())
            ->method('toArray')
            ->with(false)
            ->willReturn(['features' => []]);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->expects($this->once())
            ->method('request')
            ->willReturn($mockResponse);

        $client = new BanApiClient($mockHttpClient);

        // Act
        $result = $client->geocode('adresse inexistante zzz');

        // Assert
        $this->assertNull($result);
    }
}