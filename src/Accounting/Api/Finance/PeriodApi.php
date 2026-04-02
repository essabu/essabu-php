<?php
declare(strict_types=1);
namespace Essabu\Accounting\Api\Finance;
use Essabu\Common\BaseApi;

final class PeriodApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/periods';
    /** @return array<string, mixed> */ public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */ public function get(string $periodId): array { return $this->http->get(self::BASE_PATH . "/{$periodId}"); }
    /** @return array<string, mixed> */ public function list(string $fiscalYearId): array { return $this->http->get(self::BASE_PATH . "?fiscalYearId={$fiscalYearId}"); }
    /** @return array<string, mixed> */ public function close(string $periodId): array { return $this->http->post(self::BASE_PATH . "/{$periodId}/close"); }
    /** @return array<string, mixed> */ public function reopen(string $periodId): array { return $this->http->post(self::BASE_PATH . "/{$periodId}/reopen"); }
}
