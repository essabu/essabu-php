<?php

declare(strict_types=1);

namespace Essabu\Trade\Api;

use Essabu\Common\BaseApi;

final class PurchaseOrderApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'trade/purchase-orders';
    }
    /**
     * @return array<string, mixed>
     */
    public function approve(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/approve");
    }

    /**
     * @return array<string, mixed>
     */
    public function receive(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/receive", $data);
    }

}
