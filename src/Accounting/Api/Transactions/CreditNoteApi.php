<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api\Transactions;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;

final class CreditNoteApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/credit-notes';

    /** @return array<string, mixed> */
    public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */
    public function get(string $creditNoteId): array { return $this->http->get(self::BASE_PATH . "/{$creditNoteId}"); }
    /** @return array<string, mixed> */
    public function list(string $companyId, ?PageRequest $page = null): array { return $this->http->get($this->withPagination(self::BASE_PATH . "?companyId={$companyId}", $page)); }
    /** @return array<string, mixed> */
    public function update(string $creditNoteId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$creditNoteId}", $request); }
    public function delete(string $creditNoteId): void { $this->http->delete(self::BASE_PATH . "/{$creditNoteId}"); }
    /** @return array<string, mixed> */
    public function finalize(string $creditNoteId): array { return $this->http->post(self::BASE_PATH . "/{$creditNoteId}/finalize"); }
    /** @return array<string, mixed> */
    public function apply(string $creditNoteId): array { return $this->http->post(self::BASE_PATH . "/{$creditNoteId}/apply"); }
    public function downloadPdf(string $creditNoteId): string { return $this->http->getBytes(self::BASE_PATH . "/{$creditNoteId}/pdf"); }
}
