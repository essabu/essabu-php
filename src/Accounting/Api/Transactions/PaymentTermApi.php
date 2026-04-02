<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api\Transactions;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;

final class PaymentTermApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/payment-terms';

    /** @return array<string, mixed> */
    public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */
    public function get(string $termId): array { return $this->http->get(self::BASE_PATH . "/{$termId}"); }
    /** @return array<string, mixed> */
    public function list(string $companyId, ?PageRequest $page = null): array { return $this->http->get($this->withPagination(self::BASE_PATH . "?companyId={$companyId}", $page)); }
    /** @return array<string, mixed> */
    public function update(string $termId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$termId}", $request); }
    public function delete(string $termId): void { $this->http->delete(self::BASE_PATH . "/{$termId}"); }
}
