<?php

declare(strict_types=1);

namespace Essabu\Project\Api;

use Essabu\Common\BaseApi;

final class ReportApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'project/reports';
    }
    /**
     * @return array<string, mixed>
     */
    public function burndown(string $projectId, array $params = []): array
    {
        return $this->httpClient->get("project/projects/" . $projectId . "/burndown", $params);
    }

}
