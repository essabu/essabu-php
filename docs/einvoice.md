# E-Invoice Module Reference

The E-Invoice module provides electronic invoicing capabilities including invoice management, submission to tax authorities, verification, compliance checking, and statistics.

## Client Access

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
