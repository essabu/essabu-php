<?php

declare(strict_types=1);

namespace Essabu\Compliance\Api;

use Essabu\Common\BaseApi;

final class AuditApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'compliance/audits';
    }

    /**
     * @return array<string, mixed>
     */
    public function start(string $id): array
    {
        return $this->httpClient->post($this->basePath() . '/' . $id . '/start');
    }

    /**
     * @return array<string, mixed>
     */
    public function complete(string $id): array
    {
        return $this->httpClient->post($this->basePath() . '/' . $id . '/complete');
    }

    /**
     * @return array<string, mixed>
     */
    public function findings(string $id): array
    {
        return $this->httpClient->get($this->basePath() . '/' . $id . '/findings');
    }
}
