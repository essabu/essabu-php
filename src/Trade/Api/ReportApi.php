<?php

declare(strict_types=1);

namespace Essabu\Trade\Api;

use Essabu\Common\BaseApi;

final class ReportApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'trade/reports';
    }
    /**
     * @return array<string, mixed>
     */
    public function salesSummary(array $params = []): array
    {
        return $this->httpClient->get($this->basePath() . "/sales-summary", $params);
    }

    /**
     * @return array<string, mixed>
     */
    public function pipeline(array $params = []): array
    {
        return $this->httpClient->get($this->basePath() . "/pipeline", $params);
    }

}
