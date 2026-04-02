<?php

declare(strict_types=1);

namespace Essabu\Project\Api;

use Essabu\Common\BaseApi;

final class TaskApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'project/tasks';
    }
    /**
     * @return array<string, mixed>
     */
    public function assign(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/assign", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function complete(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/complete");
    }

    /**
     * @return array<string, mixed>
     */
    public function logTime(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/log-time", $data);
    }

}
