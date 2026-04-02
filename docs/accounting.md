# Accounting Module Reference

The Accounting module covers chart of accounts, invoicing, payments, wallets, journals, quotes, credit notes, tax rates, fiscal years, currencies, inventory, coupons, insurance, and financial reports.

## Client Access

Access the Accounting module through the main Essabu client. The `$accounting` property exposes all accounting sub-APIs as magic properties. You must first initialize the SDK with a valid API key. Returns the `AccountingClient` instance.

```php
$essabu = new EssabuClient('your-api-key');
$accounting = $essabu->accounting;
```

## Available API Classes

| Class | Accessor | Description |
|-------|----------|-------------|
| `AccountApi` | `$accounting->accounts` | Chart of accounts |
| `InvoiceApi` | `$accounting->invoices` | Invoices with finalize, send, void, mark-paid |
| `PaymentApi` | `$accounting->payments` | Payments |
| `QuoteApi` | `$accounting->quotes` | Quotes |
| `CreditNoteApi` | `$accounting->creditNotes` | Credit notes |
| `JournalApi` | `$accounting->journals` | Journals |
| `WalletApi` | `$accounting->wallets` | Wallets |
| `TaxRateApi` | `$accounting->taxRates` | Tax rates |
| `CurrencyApi` | `$accounting->currencies` | Currencies |
| `FiscalYearApi` | `$accounting->fiscalYears` | Fiscal years |
| `InsuranceApi` | `$accounting->insurances` | Insurance |
| `InventoryApi` | `$accounting->inventory` | Inventory |
| `CouponApi` | `$accounting->coupons` | Coupons |
| `ReportApi` | `$accounting->reports` | Financial reports |
| `ConfigApi` | `$accounting->config` | Configuration |
| `WebhookApi` | `$accounting->webhooks` | Webhook management |

---

## Base CRUD Methods (inherited by all APIs)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `list(?PageRequest) -> PageResponse` | `GET /api/{basePath}` | List with pagination |
| `get(string $id) -> array` | `GET /api/{basePath}/{id}` | Get by ID |
| `create(array $data) -> array` | `POST /api/{basePath}` | Create resource |
| `update(string $id, array $data) -> array` | `PATCH /api/{basePath}/{id}` | Update resource |
| `delete(string $id) -> array` | `DELETE /api/{basePath}/{id}` | Delete resource |

## InvoiceApi

Base path: `accounting/invoices`

Additional methods:

| Method | Endpoint | Description |
|--------|----------|-------------|
| `finalize(string $id) -> array` | `POST /api/accounting/invoices/{id}/finalize` | Finalize invoice |
| `send(string $id) -> array` | `POST /api/accounting/invoices/{id}/send` | Send by email |
| `void(string $id) -> array` | `POST /api/accounting/invoices/{id}/void` | Void invoice |
| `markAsPaid(string $id, array $data) -> array` | `POST /api/accounting/invoices/{id}/mark-paid` | Mark as paid |
| `downloadPdf(string $id) -> array` | `GET /api/accounting/invoices/{id}/pdf` | Download PDF |

Demonstrates the full invoicing workflow: create an invoice by providing a `customerId` and an array of line items with descriptions and amounts, then finalize it to lock the contents, send it by email to the customer, mark it as paid with a payment date, and download the PDF. The invoice starts in DRAFT status after creation and transitions through FINALIZED, SENT, and PAID. Throws `ValidationException` if items are empty, or `NotFoundException` if the invoice ID is invalid.

```php
// Full invoicing workflow
$invoice = $accounting->invoices->create([
    'customerId' => $customerId,
    'items' => [['description' => 'Service', 'amount' => 100.00]],
]);

$accounting->invoices->finalize($invoiceId);
$accounting->invoices->send($invoiceId);
$accounting->invoices->markAsPaid($invoiceId, ['paidAt' => '2026-03-26']);
$pdf = $accounting->invoices->downloadPdf($invoiceId);
```

## Standard CRUD-Only APIs

| Class | Base Path |
|-------|-----------|
| `AccountApi` | `accounting/accounts` |
| `PaymentApi` | `accounting/payments` |
| `QuoteApi` | `accounting/quotes` |
| `CreditNoteApi` | `accounting/credit-notes` |
| `JournalApi` | `accounting/journals` |
| `WalletApi` | `accounting/wallets` |
| `TaxRateApi` | `accounting/tax-rates` |
| `CurrencyApi` | `accounting/currencies` |
| `FiscalYearApi` | `accounting/fiscal-years` |
| `InsuranceApi` | `accounting/insurances` |
| `InventoryApi` | `accounting/inventory` |
| `CouponApi` | `accounting/coupons` |
| `ConfigApi` | `accounting/config` |
| `WebhookApi` | `accounting/webhooks` |

Create a journal entry by passing a date and an array of debit/credit lines that must balance. Each line requires an `accountId` and either a `debit` or `credit` amount. Use `reports->list()` to retrieve available financial reports. Returns the created resource as an associative array. Throws `ValidationException` if the journal entry lines do not balance.

```php
// Create a journal entry
$entry = $accounting->journals->create([
    'date' => '2026-03-26',
    'lines' => [
        ['accountId' => $debitAccountId, 'debit' => 1000.00],
        ['accountId' => $creditAccountId, 'credit' => 1000.00],
    ],
]);

// Get financial report
$report = $accounting->reports->list();
```

## Error Scenarios

| HTTP Status | Cause |
|-------------|-------|
| `400` | Invalid request data |
| `401` | Missing or expired authentication token |
| `403` | Insufficient permissions |
| `404` | Resource not found |
| `409` | Conflict (duplicate invoice number) |
| `422` | Business rule violation (period already closed) |
