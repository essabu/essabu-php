<?php

declare(strict_types=1);

namespace Essabu\Project\Api;

use Essabu\Common\BaseApi;

final class ReportsApi extends BaseApi
{
    /** @return array<string, mixed> */
    public function list(string $projectId, array $params = []): array { return $this->http->get("/projects/{$projectId}/reports", $params); }
    /** @return array<string, mixed> */
    public function get(string $projectId, string $reportId): array { return $this->http->get("/projects/{$projectId}/reports/{$reportId}"); }
    /** @return array<string, mixed> */
    public function create(string $projectId, array $payload): array { return $this->http->post("/projects/{$projectId}/reports", $payload); }
    public function delete(string $projectId, string $reportId): void { $this->http->delete("/projects/{$projectId}/reports/{$reportId}"); }
}
