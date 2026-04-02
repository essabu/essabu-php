<?php

declare(strict_types=1);

namespace Essabu\Asset\Api;

use Essabu\Common\BaseApi;

final class AssetApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'asset/assets';
    }
    /**
     * @return array<string, mixed>
     */
    public function assignTo(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/assign", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function dispose(string $id, array $data = []): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/dispose", $data);
    }

}
