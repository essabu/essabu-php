<?php

declare(strict_types=1);

namespace Essabu\Asset;

use Essabu\Common\HttpClient;
use Essabu\Asset\Api\AssetsApi;
use Essabu\Asset\Api\DepreciationsApi;
use Essabu\Asset\Api\MaintenanceSchedulesApi;
use Essabu\Asset\Api\MaintenanceLogsApi;
use Essabu\Asset\Api\VehiclesApi;
use Essabu\Asset\Api\FuelLogsApi;
use Essabu\Asset\Api\TripLogsApi;

/**
 * Asset module client.
 *
 * @property-read AssetsApi $assets
 * @property-read DepreciationsApi $depreciations
 * @property-read MaintenanceSchedulesApi $maintenanceSchedules
 * @property-read MaintenanceLogsApi $maintenanceLogs
 * @property-read VehiclesApi $vehicles
 * @property-read FuelLogsApi $fuelLogs
 * @property-read TripLogsApi $tripLogs
 */
final class AssetClient
{
    /** @var array<string, object> */
    private array $cache = [];

    public function __construct(
        private readonly HttpClient $http,
    ) {
    }

    public function __get(string $name): object
    {
        return match ($name) {
            'assets' => $this->resolve($name, AssetsApi::class),
            'depreciations' => $this->resolve($name, DepreciationsApi::class),
            'maintenanceSchedules' => $this->resolve($name, MaintenanceSchedulesApi::class),
            'maintenanceLogs' => $this->resolve($name, MaintenanceLogsApi::class),
            'vehicles' => $this->resolve($name, VehiclesApi::class),
            'fuelLogs' => $this->resolve($name, FuelLogsApi::class),
            'tripLogs' => $this->resolve($name, TripLogsApi::class),
            default => throw new \InvalidArgumentException("Unknown Asset API: {$name}"),
        };
    }

    private function resolve(string $name, string $class): object
    {
        return $this->cache[$name] ??= new $class($this->http);
    }
}
