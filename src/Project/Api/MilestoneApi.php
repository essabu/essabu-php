<?php

declare(strict_types=1);

namespace Essabu\Project\Api;

use Essabu\Common\BaseApi;

final class MilestoneApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'project/milestones';
    }
    /**
     * @return array<string, mixed>
     */
    public function complete(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/complete");
    }

}
