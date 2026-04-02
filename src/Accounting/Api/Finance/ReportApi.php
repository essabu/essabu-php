<?php
declare(strict_types=1);
namespace Essabu\Accounting\Api\Finance;
use Essabu\Common\BaseApi;

final class ReportApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/reports';
    /** @return array<string, mixed> */ public function trialBalance(string $companyId, string $periodId): array { return $this->http->get(self::BASE_PATH . "/trial-balance?companyId={$companyId}&periodId={$periodId}"); }
    /** @return array<string, mixed> */ public function balanceSheet(string $companyId, string $periodId): array { return $this->http->get(self::BASE_PATH . "/balance-sheet?companyId={$companyId}&periodId={$periodId}"); }
    /** @return array<string, mixed> */ public function incomeStatement(string $companyId, string $periodId): array { return $this->http->get(self::BASE_PATH . "/income-statement?companyId={$companyId}&periodId={$periodId}"); }
    /** @return array<string, mixed> */ public function generalLedger(string $companyId, string $periodId): array { return $this->http->get(self::BASE_PATH . "/general-ledger?companyId={$companyId}&periodId={$periodId}"); }
}
