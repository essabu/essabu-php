<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api\Transactions;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;

final class QuoteApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/quotes';

    /** @return array<string, mixed> */
    public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */
    public function get(string $quoteId): array { return $this->http->get(self::BASE_PATH . "/{$quoteId}"); }
    /** @return array<string, mixed> */
    public function list(string $companyId, ?PageRequest $page = null): array { return $this->http->get($this->withPagination(self::BASE_PATH . "?companyId={$companyId}", $page)); }
    /** @return array<string, mixed> */
    public function update(string $quoteId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$quoteId}", $request); }
    public function delete(string $quoteId): void { $this->http->delete(self::BASE_PATH . "/{$quoteId}"); }
    /** @return array<string, mixed> */
    public function send(string $quoteId): array { return $this->http->post(self::BASE_PATH . "/{$quoteId}/send"); }
    /** @return array<string, mixed> */
    public function accept(string $quoteId): array { return $this->http->post(self::BASE_PATH . "/{$quoteId}/accept"); }
    /** @return array<string, mixed> */
    public function reject(string $quoteId): array { return $this->http->post(self::BASE_PATH . "/{$quoteId}/reject"); }
    /** @return array<string, mixed> */
    public function convertToInvoice(string $quoteId): array { return $this->http->post(self::BASE_PATH . "/{$quoteId}/convert-to-invoice"); }
    public function downloadPdf(string $quoteId): string { return $this->http->getBytes(self::BASE_PATH . "/{$quoteId}/pdf"); }
}
