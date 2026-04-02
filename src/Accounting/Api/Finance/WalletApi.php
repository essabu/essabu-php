<?php
declare(strict_types=1);
namespace Essabu\Accounting\Api\Finance;
use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;

final class WalletApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/wallets';
    /** @return array<string, mixed> */ public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */ public function get(string $walletId): array { return $this->http->get(self::BASE_PATH . "/{$walletId}"); }
    /** @return array<string, mixed> */ public function list(string $companyId, ?PageRequest $page = null): array { return $this->http->get($this->withPagination(self::BASE_PATH . "?companyId={$companyId}", $page)); }
    /** @return array<string, mixed> */ public function update(string $walletId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$walletId}", $request); }
    public function delete(string $walletId): void { $this->http->delete(self::BASE_PATH . "/{$walletId}"); }
    /** @return array<string, mixed> */ public function freeze(string $walletId): array { return $this->http->post(self::BASE_PATH . "/{$walletId}/freeze"); }
    /** @return array<string, mixed> */ public function unfreeze(string $walletId): array { return $this->http->post(self::BASE_PATH . "/{$walletId}/unfreeze"); }
    /** @return array<string, mixed> */ public function close(string $walletId): array { return $this->http->post(self::BASE_PATH . "/{$walletId}/close"); }
}
