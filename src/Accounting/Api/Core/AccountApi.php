<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api\Core;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;

final class AccountApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/accounts';

    /** @return array<string, mixed> */
    public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */
    public function get(string $accountId): array { return $this->http->get(self::BASE_PATH . "/{$accountId}"); }
    /** @return array<string, mixed> */
    public function list(string $companyId, ?PageRequest $page = null): array { return $this->http->get($this->withPagination(self::BASE_PATH . "?companyId={$companyId}", $page)); }
    /** @return array<string, mixed> */
    public function update(string $accountId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$accountId}", $request); }
    /** @return array<string, mixed> */
    public function deactivate(string $accountId): array { return $this->http->post(self::BASE_PATH . "/{$accountId}/deactivate"); }
    public function initializeChart(string $companyId): void { $this->http->postVoid(self::BASE_PATH . "/initialize-chart?companyId={$companyId}"); }
}
