<?php

declare(strict_types=1);

namespace Essabu\Asset\Api;

use Essabu\Common\BaseApi;

final class FuelLogsApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function list(string $vehicleId, array $params = []): array { return $this->http->get("/vehicles/{$vehicleId}/fuel-logs", $params); }
    /** @return array<string, mixed> */
    public function get(string $vehicleId, string $fuelLogId): array { return $this->http->get("/vehicles/{$vehicleId}/fuel-logs/{$fuelLogId}"); }
    /** @return array<string, mixed> */
    public function create(string $vehicleId, array $payload): array { return $this->http->post("/vehicles/{$vehicleId}/fuel-logs", $payload); }
    /** @return array<string, mixed> */
    public function update(string $vehicleId, string $fuelLogId, array $payload): array { return $this->http->patch("/vehicles/{$vehicleId}/fuel-logs/{$fuelLogId}", $payload); }
    public function delete(string $vehicleId, string $fuelLogId): void { $this->http->delete("/vehicles/{$vehicleId}/fuel-logs/{$fuelLogId}"); }
}
