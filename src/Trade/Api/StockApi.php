<?php

declare(strict_types=1);

namespace Essabu\Trade\Api;

use Essabu\Common\BaseApi;

final class StockApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'trade/stocks';
    }
    /**
     * @return array<string, mixed>
     */
    public function transfer(array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/transfer", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function adjust(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/adjust", $data);
    }

}
