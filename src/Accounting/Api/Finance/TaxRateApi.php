<?php
declare(strict_types=1);
namespace Essabu\Accounting\Api\Finance;
use Essabu\Common\BaseApi;

final class TaxRateApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/tax-rates';
    /** @return array<string, mixed> */ public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */ public function get(string $taxRateId): array { return $this->http->get(self::BASE_PATH . "/{$taxRateId}"); }
    /** @return array<string, mixed> */ public function list(string $companyId): array { return $this->http->get(self::BASE_PATH . "?companyId={$companyId}"); }
    /** @return array<string, mixed> */ public function update(string $taxRateId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$taxRateId}", $request); }
    /** @return array<string, mixed> */ public function deactivate(string $taxRateId): array { return $this->http->post(self::BASE_PATH . "/{$taxRateId}/deactivate"); }
    public function delete(string $taxRateId): void { $this->http->delete(self::BASE_PATH . "/{$taxRateId}"); }
}
