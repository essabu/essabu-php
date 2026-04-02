# Asset Module Reference

The Asset module provides asset management including fixed assets, vehicles, maintenance (logs and schedules), depreciation tracking, and fleet management (fuel logs and trip logs).

## Client Access

```php
$essabu = new EssabuClient('your-api-key');
$asset = $essabu->asset;
```

## Available API Classes

| Class | Accessor | Description |
|-------|----------|-------------|
| `AssetApi` | `$asset->assets` | Fixed asset CRUD |
| `VehicleApi` | `$asset->vehicles` | Vehicle management |
| `MaintenanceScheduleApi` | `$asset->maintenanceSchedules` | Maintenance schedules |
| `MaintenanceLogApi` | `$asset->maintenanceLogs` | Maintenance logs |
| `DepreciationApi` | `$asset->depreciations` | Depreciation tracking |
| `FuelLogApi` | `$asset->fuelLogs` | Fuel log tracking |
| `TripLogApi` | `$asset->tripLogs` | Trip log tracking |

---

## Base CRUD Methods (inherited by all APIs)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `list(?PageRequest) -> PageResponse` | `GET /api/{basePath}` | List with pagination |
| `get(string $id) -> array` | `GET /api/{basePath}/{id}` | Get by ID |
| `create(array $data) -> array` | `POST /api/{basePath}` | Create resource |
| `update(string $id, array $data) -> array` | `PATCH /api/{basePath}/{id}` | Update resource |
| `delete(string $id) -> array` | `DELETE /api/{basePath}/{id}` | Delete resource |

## Standard CRUD-Only APIs

| Class | Base Path |
|-------|-----------|
| `AssetApi` | `asset/assets` |
| `VehicleApi` | `asset/vehicles` |
| `MaintenanceScheduleApi` | `asset/maintenance-schedules` |
| `MaintenanceLogApi` | `asset/maintenance-logs` |
| `DepreciationApi` | `asset/depreciations` |
| `FuelLogApi` | `asset/fuel-logs` |
| `TripLogApi` | `asset/trip-logs` |

```php
// Create an asset
$assetItem = $asset->assets->create([
    'name' => 'Office Laptop',
    'category' => 'IT Equipment',
    'purchaseDate' => '2026-01-15',
    'purchasePrice' => 1500.00,
    'depreciationMethod' => 'straight-line',
    'usefulLifeMonths' => 36,
]);

// Create a vehicle
$vehicle = $asset->vehicles->create([
    'make' => 'Toyota',
    'model' => 'Hilux',
    'year' => 2025,
    'licensePlate' => 'ABC-1234',
    'mileage' => 0,
]);

// Log maintenance
$log = $asset->maintenanceLogs->create([
    'assetId' => $vehicleId,
    'type' => 'oil-change',
    'date' => '2026-03-26',
    'cost' => 85.00,
    'notes' => 'Regular service',
]);

// Schedule recurring maintenance
$schedule = $asset->maintenanceSchedules->create([
    'assetId' => $vehicleId,
    'type' => 'oil-change',
    'intervalDays' => 90,
    'nextDueDate' => '2026-06-26',
]);

// Track depreciation
$depreciation = $asset->depreciations->list(new PageRequest(page: 1, perPage: 20));

// Log fuel purchase
$fuelLog = $asset->fuelLogs->create([
    'vehicleId' => $vehicleId,
    'liters' => 45.0,
    'costPerLiter' => 1.85,
    'odometer' => 15230,
    'date' => '2026-03-26',
]);

// Log a trip
$tripLog = $asset->tripLogs->create([
    'vehicleId' => $vehicleId,
    'driverId' => $driverId,
    'startOdometer' => 15230,
    'endOdometer' => 15380,
    'startDate' => '2026-03-26T08:00:00Z',
    'endDate' => '2026-03-26T17:00:00Z',
]);
```

## Error Scenarios

| HTTP Status | Cause |
|-------------|-------|
| `400` | Invalid request data (missing asset name, invalid date) |
| `401` | Missing or expired authentication token |
| `403` | Insufficient permissions |
| `404` | Asset, vehicle, or log not found |
| `409` | Conflict (duplicate license plate) |
| `422` | Business rule violation (end odometer less than start) |
