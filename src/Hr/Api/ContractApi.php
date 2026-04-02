<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class ContractApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/contracts';
    }
    /**
     * @return array<string, mixed>
     */
    public function renew(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/renew", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function terminate(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/terminate", $data);
    }

}
