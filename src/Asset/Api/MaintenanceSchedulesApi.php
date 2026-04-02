<?php

declare(strict_types=1);

namespace Essabu\Asset\Api;

use Essabu\Common\BaseApi;

final class MaintenanceSchedulesApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function list(string $assetId, array $params = []): array { return $this->http->get("/assets/{$assetId}/maintenance-schedules", $params); }
    /** @return array<string, mixed> */
    public function get(string $assetId, string $scheduleId): array { return $this->http->get("/assets/{$assetId}/maintenance-schedules/{$scheduleId}"); }
    /** @return array<string, mixed> */
    public function create(string $assetId, array $payload): array { return $this->http->post("/assets/{$assetId}/maintenance-schedules", $payload); }
    /** @return array<string, mixed> */
    public function update(string $assetId, string $scheduleId, array $payload): array { return $this->http->patch("/assets/{$assetId}/maintenance-schedules/{$scheduleId}", $payload); }
    public function delete(string $assetId, string $scheduleId): void { $this->http->delete("/assets/{$assetId}/maintenance-schedules/{$scheduleId}"); }
}
