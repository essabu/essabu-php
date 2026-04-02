<?php
declare(strict_types=1);
namespace Essabu\Accounting\Api\Finance;
use Essabu\Common\BaseApi;

final class ExchangeRateApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/exchange-rates';
    /** @return array<string, mixed> */ public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */ public function get(string $rateId): array { return $this->http->get(self::BASE_PATH . "/{$rateId}"); }
    /** @return array<string, mixed> */ public function list(string $companyId): array { return $this->http->get(self::BASE_PATH . "?companyId={$companyId}"); }
    /** @return array<string, mixed> */ public function update(string $rateId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$rateId}", $request); }
    public function delete(string $rateId): void { $this->http->delete(self::BASE_PATH . "/{$rateId}"); }
    /** @return array<string, mixed> */ public function fetch(string $companyId, string $sourceCurrency, string $targetCurrency): array { return $this->http->get(self::BASE_PATH . "/fetch?companyId={$companyId}&sourceCurrency={$sourceCurrency}&targetCurrency={$targetCurrency}"); }
}
