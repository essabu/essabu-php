<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api\Transactions;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;

final class JournalEntryApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/journal-entries';

    /** @return array<string, mixed> */
    public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */
    public function get(string $entryId): array { return $this->http->get(self::BASE_PATH . "/{$entryId}"); }
    /** @return array<string, mixed> */
    public function list(string $companyId, ?PageRequest $page = null): array { return $this->http->get($this->withPagination(self::BASE_PATH . "?companyId={$companyId}", $page)); }
    /** @return array<string, mixed> */
    public function update(string $entryId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$entryId}", $request); }
    public function delete(string $entryId): void { $this->http->delete(self::BASE_PATH . "/{$entryId}"); }
    /** @return array<string, mixed> */
    public function validate(string $entryId): array { return $this->http->post(self::BASE_PATH . "/{$entryId}/post"); }
    /** @return array<string, mixed> */
    public function reverse(string $entryId): array { return $this->http->post(self::BASE_PATH . "/{$entryId}/reverse"); }
}
