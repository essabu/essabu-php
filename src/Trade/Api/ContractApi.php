<?php

declare(strict_types=1);

namespace Essabu\Trade\Api;

use Essabu\Common\BaseApi;

final class ContractApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'trade/contracts';
    }
    /**
     * @return array<string, mixed>
     */
    public function sign(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/sign");
    }

    /**
     * @return array<string, mixed>
     */
    public function renew(string $id, array $data = []): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/renew", $data);
    }

}
