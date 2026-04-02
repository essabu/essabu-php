<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

final class LoanApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'hr/loans';
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
    public function disburse(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/disburse");
    }

    /**
     * @return array<string, mixed>
     */
    public function repay(string $id, array $data): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/repay", $data);
    }

}
