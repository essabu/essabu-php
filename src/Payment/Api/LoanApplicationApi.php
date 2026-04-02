<?php

declare(strict_types=1);

namespace Essabu\Payment\Api;

use Essabu\Common\BaseApi;

final class LoanApplicationApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'payment/loan-applications';
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
    public function reject(string $id, array $data = []): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/reject", $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function disburse(string $id): array
    {
        return $this->httpClient->post($this->basePath() . "/" . $id . "/disburse");
    }

}
