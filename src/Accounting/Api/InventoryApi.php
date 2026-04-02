<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api;

use Essabu\Common\BaseApi;

final class InventoryApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'accounting/inventory';
    }
    /**
     * @return array<string, mixed>
     */
    public function adjust(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/adjust", $data);
    }

}
