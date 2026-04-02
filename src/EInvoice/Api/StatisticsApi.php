<?php

declare(strict_types=1);

namespace Essabu\EInvoice\Api;

use Essabu\Common\BaseApi;

final class StatisticsApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'e-invoice/statistics';
    }
    /**
     * @return array<string, mixed>
     */
    public function overview(array $params = []): array
    {
        return $this->httpClient->get($this->basePath() . "/overview", $params);
    }

}
