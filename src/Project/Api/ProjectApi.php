<?php

declare(strict_types=1);

namespace Essabu\Project\Api;

use Essabu\Common\BaseApi;

final class ProjectApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'project/projects';
    }
    /**
     * @return array<string, mixed>
     */
    public function archive(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/archive");
    }

    /**
     * @return array<string, mixed>
     */
    public function getTimeline(string $id): array
    {
        return $this->httpClient->get($this->basePath() . "/" . $id . "/timeline");
    }

}
