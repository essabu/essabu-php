<?php

declare(strict_types=1);

namespace Essabu\Compliance\Api;

use Essabu\Common\BaseApi;

final class IncidentApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'compliance/incidents';
    }

    /**
     * @return array<string, mixed>
     */
    public function resolve(string $id): array
    {
        return $this->httpClient->post($this->basePath() . '/' . $id . '/resolve');
    }

    /**
     * @return array<string, mixed>
     */
    public function escalate(string $id): array
    {
        return $this->httpClient->post($this->basePath() . '/' . $id . '/escalate');
    }
}
