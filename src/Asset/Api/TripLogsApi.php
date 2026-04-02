<?php

declare(strict_types=1);

namespace Essabu\Asset\Api;

use Essabu\Common\BaseApi;

final class TripLogsApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function list(string $vehicleId, array $params = []): array { return $this->http->get("/vehicles/{$vehicleId}/trip-logs", $params); }
    /** @return array<string, mixed> */
    public function get(string $vehicleId, string $tripLogId): array { return $this->http->get("/vehicles/{$vehicleId}/trip-logs/{$tripLogId}"); }
    /** @return array<string, mixed> */
    public function create(string $vehicleId, array $payload): array { return $this->http->post("/vehicles/{$vehicleId}/trip-logs", $payload); }
    /** @return array<string, mixed> */
    public function update(string $vehicleId, string $tripLogId, array $payload): array { return $this->http->patch("/vehicles/{$vehicleId}/trip-logs/{$tripLogId}", $payload); }
    public function delete(string $vehicleId, string $tripLogId): void { $this->http->delete("/vehicles/{$vehicleId}/trip-logs/{$tripLogId}"); }
}
