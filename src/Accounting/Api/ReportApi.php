<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api;

use Essabu\Common\BaseApi;

final class ReportApi extends BaseApi
{
    protected function basePath(): string
    {
        return 'accounting/reports';
    }
    /**
     * @return array<string, mixed>
     */
    public function balanceSheet(array $params = []): array
    {
        return $this->httpClient->get($this->basePath() . "/balance-sheet", $params);
    }

    /**
     * @return array<string, mixed>
     */
    public function profitAndLoss(array $params = []): array
    {
        return $this->httpClient->get($this->basePath() . "/profit-and-loss", $params);
    }

    /**
     * @return array<string, mixed>
     */
    public function trialBalance(array $params = []): array
    {
        return $this->httpClient->get($this->basePath() . "/trial-balance", $params);
    }

    /**
     * @return array<string, mixed>
     */
    public function cashFlow(array $params = []): array
    {
        return $this->httpClient->get($this->basePath() . "/cash-flow", $params);
    }

}
