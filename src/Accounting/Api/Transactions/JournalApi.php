<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api\Transactions;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;

final class JournalApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/journals';

    /** @return array<string, mixed> */
    public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */
    public function get(string $journalId): array { return $this->http->get(self::BASE_PATH . "/{$journalId}"); }
    /** @return array<string, mixed> */
    public function list(string $companyId, ?PageRequest $page = null): array { return $this->http->get($this->withPagination(self::BASE_PATH . "?companyId={$companyId}", $page)); }
    /** @return array<string, mixed> */
    public function update(string $journalId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$journalId}", $request); }
    public function delete(string $journalId): void { $this->http->delete(self::BASE_PATH . "/{$journalId}"); }
}
