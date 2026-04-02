<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class ReportApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/reports';
    }
    /**
     * @return array<string, mixed>
     */
    public function generate(string $type, array $params = []): array
    {
        return $this->httpClient->post($this->basePath() . "/generate", array_merge(["type" => $type], $params));
    }

}
