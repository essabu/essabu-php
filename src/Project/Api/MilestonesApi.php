<?php

declare(strict_types=1);

namespace Essabu\Project\Api;

use Essabu\Common\BaseApi;

final class MilestonesApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function list(string $projectId, array $params = []): array { return $this->http->get("/projects/{$projectId}/milestones", $params); }
    /** @return array<string, mixed> */
    public function get(string $projectId, string $milestoneId): array { return $this->http->get("/projects/{$projectId}/milestones/{$milestoneId}"); }
    /** @return array<string, mixed> */
    public function create(string $projectId, array $payload): array { return $this->http->post("/projects/{$projectId}/milestones", $payload); }
    /** @return array<string, mixed> */
    public function update(string $projectId, string $milestoneId, array $payload): array { return $this->http->patch("/projects/{$projectId}/milestones/{$milestoneId}", $payload); }
    public function delete(string $projectId, string $milestoneId): void { $this->http->delete("/projects/{$projectId}/milestones/{$milestoneId}"); }
}
