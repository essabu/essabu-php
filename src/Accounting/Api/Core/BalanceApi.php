<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api\Core;

use Essabu\Common\BaseApi;

final class BalanceApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/balances';

    /** @return array<string, mixed> */
    public function getAccountBalances(string $companyId, string $periodId): array { return $this->http->get(self::BASE_PATH . "/accounts?companyId={$companyId}&periodId={$periodId}"); }
    /** @return array<string, mixed> */
    public function getOpeningBalances(string $companyId, string $fiscalYearId): array { return $this->http->get(self::BASE_PATH . "/opening?companyId={$companyId}&fiscalYearId={$fiscalYearId}"); }
    /** @return array<string, mixed> */
    public function createOpeningBalance(array $request): array { return $this->http->post(self::BASE_PATH . '/opening', $request); }
    public function carryForward(string $companyId, string $fromFiscalYearId, string $toFiscalYearId): void { $this->http->postVoid(self::BASE_PATH . "/carry-forward?companyId={$companyId}&fromFiscalYearId={$fromFiscalYearId}&toFiscalYearId={$toFiscalYearId}"); }
}
