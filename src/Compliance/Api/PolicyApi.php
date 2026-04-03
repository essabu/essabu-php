<?php

declare(strict_types=1);

namespace Essabu\Compliance\Api;

use Essabu\Common\BaseApi;

final class PolicyApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'compliance/policies';
    }

    /**
     * @return array<string, mixed>
     */
    public function publish(string $id): array
    {
        return $this->httpClient->post($this->basePath() . '/' . $id . '/publish');
    }

    /**
     * @return array<string, mixed>
     */
    public function archive(string $id): array
    {
        return $this->httpClient->post($this->basePath() . '/' . $id . '/archive');
    }
}
