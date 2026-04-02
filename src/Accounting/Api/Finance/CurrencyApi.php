<?php
declare(strict_types=1);
namespace Essabu\Accounting\Api\Finance;
use Essabu\Common\BaseApi;

final class CurrencyApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/currencies';
    /** @return array<string, mixed> */ public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */ public function get(string $currencyId): array { return $this->http->get(self::BASE_PATH . "/{$currencyId}"); }
    /** @return array<string, mixed> */ public function list(): array { return $this->http->get(self::BASE_PATH); }
    /** @return array<string, mixed> */ public function update(string $currencyId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$currencyId}", $request); }
    public function delete(string $currencyId): void { $this->http->delete(self::BASE_PATH . "/{$currencyId}"); }
    /** @return array<string, mixed> */ public function setDefault(string $currencyId): array { return $this->http->post(self::BASE_PATH . "/{$currencyId}/set-default"); }
}
