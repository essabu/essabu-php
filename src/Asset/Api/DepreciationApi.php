<?php

declare(strict_types=1);

namespace Essabu\Asset\Api;

use Essabu\Common\BaseApi;

final class DepreciationApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'asset/depreciations';
    }
    /**
     * @return array<string, mixed>
     */
    public function calculate(string $assetId): array
    {
        return $this->httpClient->post("asset/assets/" . $assetId . "/depreciation/calculate");
    }

    /**
     * @return array<string, mixed>
     */
    public function getSchedule(string $assetId): array
    {
        return $this->httpClient->get("asset/assets/" . $assetId . "/depreciation/schedule");
    }

}
