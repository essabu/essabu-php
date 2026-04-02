<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api;

use Essabu\Common\BaseApi;

final class WalletApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'accounting/wallets';
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
    public function getTransactions(string $id): array
    {
        return $this->httpClient->get($this->basePath() . "/" . $id . "/transactions");
    }

}
