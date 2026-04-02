<?php

declare(strict_types=1);

namespace Essabu\Asset;

use Essabu\Asset\Api\AssetApi;
use Essabu\Asset\Api\DepreciationApi;
use Essabu\Asset\Api\FuelLogApi;
use Essabu\Asset\Api\MaintenanceLogApi;
use Essabu\Asset\Api\MaintenanceScheduleApi;
use Essabu\Asset\Api\TripLogApi;
use Essabu\Asset\Api\VehicleApi;
use Essabu\Common\HttpClient;

/**
 * @property-read AssetApi $assets
 * @property-read VehicleApi $vehicles
 * @property-read MaintenanceScheduleApi $maintenanceSchedules
 * @property-read MaintenanceLogApi $maintenanceLogs
 * @property-read DepreciationApi $depreciations
 * @property-read FuelLogApi $fuelLogs
 * @property-read TripLogApi $tripLogs
 */
final class AssetClient
{
    /** @var array<string, object> */
    private array $instances = [];

    public function __construct(
        private readonly HttpClient $httpClient,
    ) {
    }

    public function __get(string $name): object
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        $this->instances[$name] = match ($name) {
            'assets' => new AssetApi($this->httpClient),
            'vehicles' => new VehicleApi($this->httpClient),
            'maintenanceSchedules' => new MaintenanceScheduleApi($this->httpClient),
            'maintenanceLogs' => new MaintenanceLogApi($this->httpClient),
            'depreciations' => new DepreciationApi($this->httpClient),
            'fuelLogs' => new FuelLogApi($this->httpClient),
            'tripLogs' => new TripLogApi($this->httpClient),
            default => throw new \InvalidArgumentException("Unknown Asset API: {$name}"),
        };

        return $this->instances[$name];
    }
}
