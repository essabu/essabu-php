<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class PerformanceApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/performances';
    }
    /**
     * @return array<string, mixed>
     */
    public function submitReview(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/review", $data);
    }

}
