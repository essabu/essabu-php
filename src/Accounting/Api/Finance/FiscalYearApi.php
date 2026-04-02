<?php
declare(strict_types=1);
namespace Essabu\Accounting\Api\Finance;
use Essabu\Common\BaseApi;

final class FiscalYearApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/fiscal-years';
    /** @return array<string, mixed> */ public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */ public function get(string $fiscalYearId): array { return $this->http->get(self::BASE_PATH . "/{$fiscalYearId}"); }
    /** @return array<string, mixed> */ public function list(string $companyId): array { return $this->http->get(self::BASE_PATH . "?companyId={$companyId}"); }
    /** @return array<string, mixed> */ public function update(string $fiscalYearId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$fiscalYearId}", $request); }
    /** @return array<string, mixed> */ public function close(string $fiscalYearId): array { return $this->http->post(self::BASE_PATH . "/{$fiscalYearId}/close"); }
    /** @return array<string, mixed> */ public function reopen(string $fiscalYearId): array { return $this->http->post(self::BASE_PATH . "/{$fiscalYearId}/reopen"); }
    /** @return array<string, mixed> */ public function getCurrent(string $companyId): array { return $this->http->get(self::BASE_PATH . "/current?companyId={$companyId}"); }
}
