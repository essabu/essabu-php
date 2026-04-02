<?php

declare(strict_types=1);

namespace Essabu\Trade\Api;

use Essabu\Common\BaseApi;

final class SalesOrderApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'trade/sales-orders';
    }
    /**
     * @return array<string, mixed>
     */
    public function confirm(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/confirm");
    }

    /**
     * @return array<string, mixed>
     */
    public function cancel(string $id, array $data = []): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/cancel", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function fulfill(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/fulfill");
    }

}
