<?php
declare(strict_types=1);
namespace Essabu\Accounting\Api\Finance;
use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;

final class WalletTransactionApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/wallet-transactions';
    /** @return array<string, mixed> */ public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */ public function get(string $transactionId): array { return $this->http->get(self::BASE_PATH . "/{$transactionId}"); }
    /** @return array<string, mixed> */ public function list(string $walletId, ?PageRequest $page = null): array { return $this->http->get($this->withPagination(self::BASE_PATH . "?walletId={$walletId}", $page)); }
    /** @return array<string, mixed> */ public function confirm(string $transactionId): array { return $this->http->post(self::BASE_PATH . "/{$transactionId}/confirm"); }
    /** @return array<string, mixed> */ public function cancel(string $transactionId): array { return $this->http->post(self::BASE_PATH . "/{$transactionId}/cancel"); }
}
