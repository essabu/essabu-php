<?php

declare(strict_types=1);

namespace Essabu\Accounting\Api\Transactions;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;

final class InvoiceApi extends BaseApi
{
    private const BASE_PATH = '/api/accounting/invoices';

    /** @return array<string, mixed> */
    public function create(array $request): array { return $this->http->post(self::BASE_PATH, $request); }
    /** @return array<string, mixed> */
    public function get(string $invoiceId): array { return $this->http->get(self::BASE_PATH . "/{$invoiceId}"); }
    /** @return array<string, mixed> */
    public function list(string $companyId, ?PageRequest $page = null): array { return $this->http->get($this->withPagination(self::BASE_PATH . "?companyId={$companyId}", $page)); }
    /** @return array<string, mixed> */
    public function update(string $invoiceId, array $request): array { return $this->http->put(self::BASE_PATH . "/{$invoiceId}", $request); }
    public function delete(string $invoiceId): void { $this->http->delete(self::BASE_PATH . "/{$invoiceId}"); }
    /** @return array<string, mixed> */
    public function finalize(string $invoiceId): array { return $this->http->post(self::BASE_PATH . "/{$invoiceId}/finalize"); }
    /** @return array<string, mixed> */
    public function send(string $invoiceId): array { return $this->http->post(self::BASE_PATH . "/{$invoiceId}/send"); }
    /** @return array<string, mixed> */
    public function markAsPaid(string $invoiceId): array { return $this->http->post(self::BASE_PATH . "/{$invoiceId}/mark-as-paid"); }
    /** @return array<string, mixed> */
    public function cancel(string $invoiceId): array { return $this->http->post(self::BASE_PATH . "/{$invoiceId}/cancel"); }
    public function downloadPdf(string $invoiceId): string { return $this->http->getBytes(self::BASE_PATH . "/{$invoiceId}/pdf"); }
    /** @return array<string, mixed> */
    public function createRecurring(array $request): array { return $this->http->post(self::BASE_PATH . '/recurring', $request); }
    /** @return array<string, mixed> */
    public function listRecurring(string $companyId, ?PageRequest $page = null): array { return $this->http->get($this->withPagination(self::BASE_PATH . "/recurring?companyId={$companyId}", $page)); }
    /** @return array<string, mixed> */
    public function updateRecurring(string $recurringId, array $request): array { return $this->http->put(self::BASE_PATH . "/recurring/{$recurringId}", $request); }
    public function deleteRecurring(string $recurringId): void { $this->http->delete(self::BASE_PATH . "/recurring/{$recurringId}"); }
    /** @return array<string, mixed> */
    public function activateRecurring(string $recurringId): array { return $this->http->post(self::BASE_PATH . "/recurring/{$recurringId}/activate"); }
    /** @return array<string, mixed> */
    public function deactivateRecurring(string $recurringId): array { return $this->http->post(self::BASE_PATH . "/recurring/{$recurringId}/deactivate"); }
}
