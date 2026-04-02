# E-Invoice Module Reference

The E-Invoice module provides electronic invoicing capabilities including invoice management, submission to tax authorities, verification, compliance checking, and statistics.

## Client Access

Access the E-Invoice module through the main Essabu client. The `$einvoice` property exposes all e-invoicing sub-APIs as magic properties. You must first initialize the SDK with a valid API key. Returns the `EInvoiceClient` instance.

```php
$essabu = new EssabuClient('your-api-key');
$einvoice = $essabu->einvoice;
```

## Available API Classes

| Class | Accessor | Description |
|-------|----------|-------------|
| `InvoiceApi` | `$einvoice->invoices` | E-invoice management with sign and XML download |
| `SubmissionApi` | `$einvoice->submissions` | Submit and track e-invoice submissions |
| `VerificationApi` | `$einvoice->verifications` | Verify e-invoices |
| `ComplianceApi` | `$einvoice->compliance` | Compliance checks and rules |
| `StatisticsApi` | `$einvoice->statistics` | E-invoicing statistics |

---

## InvoiceApi

Base path: `e-invoice/invoices`

Additional methods:

| Method | Endpoint | Description |
|--------|----------|-------------|
| `sign(string $id) -> array` | `POST /api/e-invoice/invoices/{id}/sign` | Sign e-invoice |
| `downloadXml(string $id) -> array` | `GET /api/e-invoice/invoices/{id}/xml` | Download XML |

Create an e-invoice record by passing the `invoiceId` (from the Accounting module), the `buyerTin` (tax identification number), and the `buyerName`. Sign the e-invoice to generate a cryptographic signature, and download the signed XML representation. Returns the e-invoice as an associative array. Throws `ValidationException` if required tax fields are missing, or `NotFoundException` if the invoice ID is invalid.

```php
// Create and sign an e-invoice
$einv = $einvoice->invoices->create([
    'invoiceId' => $invoiceId,
    'buyerTin' => '123456789',
    'buyerName' => 'Acme Corp',
]);
$einvoice->invoices->sign($einvId);
$xml = $einvoice->invoices->downloadXml($einvId);
```

## SubmissionApi

Base path: `e-invoice/submissions`

Additional methods:

| Method | Endpoint | Description |
|--------|----------|-------------|
| `submit(string $id) -> array` | `POST /api/e-invoice/submissions/{id}/submit` | Submit to authority |
| `getStatus(string $id) -> array` | `GET /api/e-invoice/submissions/{id}/status` | Check status |

Create a submission record linked to a signed e-invoice, then submit it to the tax authority. Use `getStatus` to poll the submission status (e.g., `pending`, `accepted`, `rejected`). Returns the submission with its current status. Throws a 409 `Conflict` if the e-invoice has already been submitted, or a 502 error if the tax authority service is unavailable.

```php
$submission = $einvoice->submissions->create(['invoiceId' => $einvId]);
$einvoice->submissions->submit($submissionId);
$status = $einvoice->submissions->getStatus($submissionId);
```

## VerificationApi

Base path: `e-invoice/verifications`

Additional methods:

| Method | Endpoint | Description |
|--------|----------|-------------|
| `verify(array $data) -> array` | `POST /api/e-invoice/verifications/verify` | Verify an e-invoice |

Verify an e-invoice independently by passing its unique verification `code`. Returns a result array indicating whether the invoice is valid, along with the invoice details if found. Throws `NotFoundException` if the verification code does not match any e-invoice.

```php
$result = $einvoice->verifications->verify(['code' => 'INV-2026-001-ABCDEF']);
```

## ComplianceApi

Base path: `e-invoice/compliance`

Additional methods:

| Method | Endpoint | Description |
|--------|----------|-------------|
| `check(array $data) -> array` | `POST /api/e-invoice/compliance/check` | Check compliance |
| `getRules(string $country) -> array` | `GET /api/e-invoice/compliance/rules/{country}` | Get country rules |

Check whether an e-invoice meets the compliance requirements of its jurisdiction by passing the `invoiceId`. Retrieve country-specific e-invoicing rules by passing the two-letter country code (e.g., `BI` for Burundi). Returns a compliance result with any violations listed. Throws `ValidationException` if the invoice data is incomplete for compliance checking.

```php
$compliance = $einvoice->compliance->check(['invoiceId' => $einvId]);
$rules = $einvoice->compliance->getRules('BI');
```

## StatisticsApi

Base path: `e-invoice/statistics`

Additional methods:

| Method | Endpoint | Description |
|--------|----------|-------------|
| `overview(array $params) -> array` | `GET /api/e-invoice/statistics/overview` | Get overview stats |

Retrieve an overview of e-invoicing statistics for a given date range. Pass `startDate` and `endDate` as query parameters. Returns aggregated counts of submitted, accepted, and rejected e-invoices along with total amounts. Throws `BadRequestException` if the date range is invalid.

```php
$stats = $einvoice->statistics->overview(['startDate' => '2026-01-01', 'endDate' => '2026-03-31']);
```

## Typical Workflow

1. Create an invoice in the Accounting module
2. Create an e-invoice record via `$einvoice->invoices->create()`
3. Sign it via `$einvoice->invoices->sign()`
4. Create a submission via `$einvoice->submissions->create()`
5. Submit to tax authority via `$einvoice->submissions->submit()`
6. Check status via `$einvoice->submissions->getStatus()`
7. Verify independently via `$einvoice->verifications->verify()`

## Error Scenarios

| HTTP Status | Cause |
|-------------|-------|
| `400` | Invalid invoice data (missing required tax fields) |
| `401` | Missing or expired authentication token |
| `403` | Insufficient permissions |
| `404` | E-invoice not found |
| `409` | Already submitted or already cancelled |
| `422` | Validation failure from tax authority |
| `502` | Tax authority service unavailable |
