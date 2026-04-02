<?php

declare(strict_types=1);

namespace Essabu\Project\Api;

use Essabu\Common\BaseApi;

final class TaskCommentsApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function list(string $taskId, array $params = []): array { return $this->http->get("/tasks/{$taskId}/comments", $params); }
    /** @return array<string, mixed> */
    public function get(string $taskId, string $commentId): array { return $this->http->get("/tasks/{$taskId}/comments/{$commentId}"); }
    /** @return array<string, mixed> */
    public function create(string $taskId, array $payload): array { return $this->http->post("/tasks/{$taskId}/comments", $payload); }
    /** @return array<string, mixed> */
    public function update(string $taskId, string $commentId, array $payload): array { return $this->http->patch("/tasks/{$taskId}/comments/{$commentId}", $payload); }
    public function delete(string $taskId, string $commentId): void { $this->http->delete("/tasks/{$taskId}/comments/{$commentId}"); }
}
