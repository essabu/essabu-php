<?php

declare(strict_types=1);

namespace Essabu\Project\Api;

use Essabu\Common\BaseApi;

final class TasksApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function list(string $projectId, array $params = []): array { return $this->http->get("/projects/{$projectId}/tasks", $params); }
    /** @return array<string, mixed> */
    public function get(string $projectId, string $taskId): array { return $this->http->get("/projects/{$projectId}/tasks/{$taskId}"); }
    /** @return array<string, mixed> */
    public function create(string $projectId, array $payload): array { return $this->http->post("/projects/{$projectId}/tasks", $payload); }
    /** @return array<string, mixed> */
    public function update(string $projectId, string $taskId, array $payload): array { return $this->http->patch("/projects/{$projectId}/tasks/{$taskId}", $payload); }
    public function delete(string $projectId, string $taskId): void { $this->http->delete("/projects/{$projectId}/tasks/{$taskId}"); }
}
