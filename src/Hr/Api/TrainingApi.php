<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class TrainingApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/trainings';
    }
    /**
     * @return array<string, mixed>
     */
    public function enroll(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/enroll", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function complete(string $id, array $data = []): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/complete", $data);
    }

}
