<?php

declare(strict_types=1);

namespace Essabu\Payment\Api;

use Essabu\Common\BaseApi;

final class FinancialAccountApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'payment/financial-accounts';
    }
    /**
     * @return array<string, mixed>
     */
    public function getBalance(string $id): array
    {
        return $this->httpClient->get($this->basePath() . "/" . $id . "/balance");
    }

    /**
     * @return array<string, mixed>
     */
    public function getStatement(string $id, array $params = []): array
    {
        return $this->httpClient->get($this->basePath() . "/" . $id . "/statement", $params);
    }

}
