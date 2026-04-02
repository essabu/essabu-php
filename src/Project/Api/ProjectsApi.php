<?php

declare(strict_types=1);

namespace Essabu\Project\Api;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageResponse;

final class ProjectsApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function list(array $params = []): array { return $this->http->get('/projects', $params); }
    /** @return array<string, mixed> */
    public function get(string $projectId): array { return $this->http->get("/projects/{$projectId}"); }
    /** @return array<string, mixed> */
    public function create(array $payload): array { return $this->http->post('/projects', $payload); }
    /** @return array<string, mixed> */
    public function update(string $projectId, array $payload): array { return $this->http->patch("/projects/{$projectId}", $payload); }
    public function delete(string $projectId): void { $this->http->delete("/projects/{$projectId}"); }
}
