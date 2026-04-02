<?php

declare(strict_types=1);

namespace Essabu\Trade\Api;

use Essabu\Common\BaseApi;

final class CampaignApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'trade/campaigns';
    }
    /**
     * @return array<string, mixed>
     */
    public function launch(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/launch");
    }

    /**
     * @return array<string, mixed>
     */
    public function pause(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/pause");
    }

}
