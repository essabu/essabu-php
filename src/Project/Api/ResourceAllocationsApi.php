<?php

declare(strict_types=1);

namespace Essabu\Project\Api;

use Essabu\Common\BaseApi;

final class ResourceAllocationsApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function list(string $projectId, array $params = []): array { return $this->http->get("/projects/{$projectId}/resource-allocations", $params); }
    /** @return array<string, mixed> */
    public function get(string $projectId, string $allocationId): array { return $this->http->get("/projects/{$projectId}/resource-allocations/{$allocationId}"); }
    /** @return array<string, mixed> */
    public function create(string $projectId, array $payload): array { return $this->http->post("/projects/{$projectId}/resource-allocations", $payload); }
    /** @return array<string, mixed> */
    public function update(string $projectId, string $allocationId, array $payload): array { return $this->http->patch("/projects/{$projectId}/resource-allocations/{$allocationId}", $payload); }
    public function delete(string $projectId, string $allocationId): void { $this->http->delete("/projects/{$projectId}/resource-allocations/{$allocationId}"); }
}
