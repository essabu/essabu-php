<?php

declare(strict_types=1);

namespace Essabu\Asset\Api;

use Essabu\Common\BaseApi;

final class MaintenanceLogsApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function list(string $assetId, array $params = []): array { return $this->http->get("/assets/{$assetId}/maintenance-logs", $params); }
    /** @return array<string, mixed> */
    public function get(string $assetId, string $logId): array { return $this->http->get("/assets/{$assetId}/maintenance-logs/{$logId}"); }
    /** @return array<string, mixed> */
    public function create(string $assetId, array $payload): array { return $this->http->post("/assets/{$assetId}/maintenance-logs", $payload); }
    /** @return array<string, mixed> */
    public function update(string $assetId, string $logId, array $payload): array { return $this->http->patch("/assets/{$assetId}/maintenance-logs/{$logId}", $payload); }
    public function delete(string $assetId, string $logId): void { $this->http->delete("/assets/{$assetId}/maintenance-logs/{$logId}"); }
}
