<?php

declare(strict_types=1);

namespace Essabu\Asset\Api;

use Essabu\Common\BaseApi;

final class DepreciationsApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function list(string $assetId, array $params = []): array { return $this->http->get("/assets/{$assetId}/depreciations", $params); }
    /** @return array<string, mixed> */
    public function get(string $assetId, string $depreciationId): array { return $this->http->get("/assets/{$assetId}/depreciations/{$depreciationId}"); }
    /** @return array<string, mixed> */
    public function create(string $assetId, array $payload): array { return $this->http->post("/assets/{$assetId}/depreciations", $payload); }
    /** @return array<string, mixed> */
    public function update(string $assetId, string $depreciationId, array $payload): array { return $this->http->patch("/assets/{$assetId}/depreciations/{$depreciationId}", $payload); }
    public function delete(string $assetId, string $depreciationId): void { $this->http->delete("/assets/{$assetId}/depreciations/{$depreciationId}"); }
}
