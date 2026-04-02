<?php

declare(strict_types=1);

namespace Essabu\Trade\Api;

use Essabu\Common\BaseApi;

final class OpportunityApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'trade/opportunities';
    }
    /**
     * @return array<string, mixed>
     */
    public function won(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/won");
    }

    /**
     * @return array<string, mixed>
     */
    public function lost(string $id, array $data = []): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/lost", $data);
    }

}
