<?php
declare(strict_types=1);
namespace Essabu\Accounting\Api\Finance;
use Essabu\Common\BaseApi;

final class ExchangeRateProviderApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/exchange-rate-providers';
    /** @return array<string, mixed> */ public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */ public function get(string $providerId): array { return $this->http->get(self::BASE_PATH . "/{$providerId}"); }
    /** @return array<string, mixed> */ public function list(string $companyId): array { return $this->http->get(self::BASE_PATH . "?companyId={$companyId}"); }
    /** @return array<string, mixed> */ public function update(string $providerId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$providerId}", $request); }
    public function delete(string $providerId): void { $this->http->delete(self::BASE_PATH . "/{$providerId}"); }
}
