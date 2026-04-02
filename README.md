# Essabu PHP SDK

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-8892BF.svg)](https://www.php.net/)
[![Packagist Version](https://img.shields.io/packagist/v/essabu/essabu.svg)](https://packagist.org/packages/essabu/essabu)
[![License](https://img.shields.io/packagist/l/essabu/essabu.svg)](https://github.com/essabu/essabu-php/blob/main/LICENSE)
[![CI](https://github.com/essabu/essabu-php/actions/workflows/ci.yml/badge.svg)](https://github.com/essabu/essabu-php/actions/workflows/ci.yml)

The Essabu PHP library provides convenient access to the Essabu API from applications written in PHP. It includes a unified client for all 8 Essabu modules: HR, Accounting, Identity, Trade, Payment, E-Invoice, Project, and Asset.

## Requirements

- PHP 8.2+
- [Guzzle](https://docs.guzzlephp.org/) 7.5+

## Installation

Install via [Composer](https://getcomposer.org/):

```bash
composer require essabu/essabu
```

## Quick Start

```php
use Essabu\Essabu;

$essabu = new Essabu('sk_live_your_api_key', 'your_tenant_id');

// HR - Create an employee
$employee = $essabu->hr->employees->create([
    'firstName' => 'Jean',
    'lastName'  => 'Mukendi',
    'email'     => 'jean.mukendi@example.com',
]);

// Accounting - Create and finalize an invoice
$invoice = $essabu->accounting->invoices->create([
    'customerId' => 'cust_123',
    'items'      => [['description' => 'Consulting', 'quantity' => 1, 'unitPrice' => 5000]],
]);
$essabu->accounting->invoices->finalize($invoice['id']);

// Identity - Authenticate a user
$token = $essabu->identity->auth->login([
    'email'    => 'user@example.com',
    'password' => 'secret',
]);

// Trade - Manage CRM pipeline
$contact = $essabu->trade->contacts->create([
    'firstName' => 'Pierre',
    'email'     => 'pierre@globalcorp.cd',
]);
$opportunity = $essabu->trade->opportunities->create([
    'contactId' => $contact['id'],
    'name'      => 'ERP Deal',
    'value'     => 75000,
]);

// Payment - Process payments
$intent = $essabu->payment->paymentIntents->create([
    'amount'   => 10000,
    'currency' => 'USD',
]);

// E-Invoice - Submit to government
$submission = $essabu->eInvoice->submissions->submit('inv_123');

// Project - Track projects
$project = $essabu->project->projects->create(['name' => 'New Website']);

// Asset - Manage company assets
$assets = $essabu->asset->assets->list();
```

## Configuration

### Basic

```php
$essabu = new Essabu(
    apiKey: 'sk_live_your_api_key',
    tenantId: 'your_tenant_id',
);
```

### Advanced

```php
$essabu = new Essabu(
    apiKey: 'sk_live_your_api_key',
    tenantId: 'your_tenant_id',
    baseUrl: 'https://api.essabu.com',   // Custom API endpoint
    connectTimeout: 5.0,                  // Connection timeout (seconds)
    readTimeout: 30.0,                    // Read timeout (seconds)
    maxRetries: 3,                        // Auto-retry on 5xx errors
);
```

### Using a Config Object

```php
use Essabu\EssabuConfig;

$config = new EssabuConfig(
    apiKey: 'sk_live_your_api_key',
    tenantId: 'your_tenant_id',
    baseUrl: 'https://api.essabu.com',
);

$essabu = Essabu::fromConfig($config);
```

### Per-Module Access

Each module is lazily initialized and cached. Access any module as a property:

```php
$essabu->hr;          // HrClient
$essabu->accounting;  // AccountingClient
$essabu->identity;    // IdentityClient
$essabu->trade;       // TradeClient
$essabu->payment;     // PaymentClient
$essabu->eInvoice;    // EInvoiceClient
$essabu->project;     // ProjectClient
$essabu->asset;       // AssetClient
```

## Modules

| Module | Property | Key APIs |
|--------|----------|----------|
| **HR** | `$essabu->hr` | employees, departments, positions, contracts, attendances, leaves, shifts, payrolls, expenses, recruitment, performance, onboarding, documents, benefits, loans, timesheets, skills, trainings, reports, webhooks, config, history |
| **Accounting** | `$essabu->accounting` | accounts, balances, config, invoices, quotes, creditNotes, payments, paymentTerms, journals, journalEntries, taxRates, currencies, exchangeRates, fiscalYears, periods, reports, wallets, insurancePartners, insuranceContracts, insuranceClaims, priceLists, suppliers, inventory, purchaseOrders, batches, stockMovements, stockCounts, stockLocations, webhooks |
| **Identity** | `$essabu->identity` | auth, users, profiles, companies, tenants, roles, permissions, branches, apiKeys, sessions |
| **Trade** | `$essabu->trade` | customers, products, salesOrders, deliveries, receipts, suppliers, purchaseOrders, inventory, stock, warehouses, contacts, opportunities, activities, campaigns, contracts, documents |
| **Payment** | `$essabu->payment` | paymentIntents, paymentAccounts, transactions, refunds, subscriptions, subscriptionPlans, financialAccounts, loanProducts, loanApplications, loanRepayments, collaterals, kycProfiles, kycDocuments, reports |
| **E-Invoice** | `$essabu->eInvoice` | invoices, submissions, verification, compliance, statistics |
| **Project** | `$essabu->project` | projects, milestones, tasks, taskComments, resourceAllocations, reports |
| **Asset** | `$essabu->asset` | assets, depreciations, maintenanceSchedules, maintenanceLogs, vehicles, fuelLogs, tripLogs |

## Pagination

All list endpoints support pagination:

```php
use Essabu\Common\Model\PageRequest;

// Simple pagination
$page = PageRequest::of(page: 0, size: 50);
$result = $essabu->hr->employees->list($page);

echo $result->totalElements;  // Total items across all pages
echo $result->totalPages;     // Total number of pages

foreach ($result->content as $employee) {
    echo $employee['firstName'];
}

// With sorting
$sorted = new PageRequest(page: 0, size: 20, sort: 'createdAt', direction: 'desc');
$result = $essabu->hr->employees->list($sorted);

// Navigate pages
if ($result->hasNext()) {
    $nextPage = PageRequest::of($result->page + 1, $result->size);
    $next = $essabu->hr->employees->list($nextPage);
}
```

## Error Handling

The SDK throws specific exceptions for different HTTP error codes. All exceptions extend `EssabuException`:

```php
use Essabu\Common\Exception\EssabuException;
use Essabu\Common\Exception\NotFoundException;
use Essabu\Common\Exception\ValidationException;
use Essabu\Common\Exception\UnauthorizedException;
use Essabu\Common\Exception\ForbiddenException;
use Essabu\Common\Exception\RateLimitException;
use Essabu\Common\Exception\ServerException;

try {
    $employee = $essabu->hr->employees->get('non-existent-id');
} catch (ValidationException $e) {
    // 400/422 - Validation error with field details
    echo "Validation failed: {$e->getMessage()}\n";
    foreach ($e->fieldErrors as $field => $error) {
        echo "  {$field}: {$error}\n";
    }
} catch (UnauthorizedException $e) {
    // 401 - Invalid or expired API key
} catch (ForbiddenException $e) {
    // 403 - Insufficient permissions
} catch (NotFoundException $e) {
    // 404 - Resource not found
} catch (RateLimitException $e) {
    // 429 - Rate limited, retry after $e->retryAfter seconds
    sleep((int) $e->retryAfter);
} catch (ServerException $e) {
    // 5xx - Server error (after automatic retries)
} catch (EssabuException $e) {
    // Catch-all for any API error
    echo "Error ({$e->statusCode}): {$e->getMessage()}\n";
}
```

### Automatic Retries

The SDK automatically retries on 5xx server errors with exponential backoff. Configure the maximum number of retries:

```php
$essabu = new Essabu(
    apiKey: 'your-key',
    tenantId: 'your-tenant',
    maxRetries: 5,  // Default: 3
);
```

## Webhooks

Verify and process incoming webhook events:

```php
use Essabu\Common\WebhookVerifier;

$verifier = new WebhookVerifier('whsec_your_webhook_secret');

$payload   = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_ESSABU_SIGNATURE'] ?? '';

if (!$verifier->verify($payload, $signature)) {
    http_response_code(400);
    exit('Invalid signature');
}

$event = json_decode($payload, true);

match ($event['type']) {
    'hr.employee.created'             => handleNewEmployee($event['data']),
    'accounting.invoice.paid'         => handleInvoicePaid($event['data']),
    'payment.intent.succeeded'        => handlePaymentSuccess($event['data']),
    'einvoice.submission.accepted'    => handleEInvoiceAccepted($event['data']),
    default                           => null,
};

http_response_code(200);
```

See [`examples/webhook_handler.php`](examples/webhook_handler.php) for a complete implementation.

## Examples

The [`examples/`](examples/) directory contains runnable scripts for common use cases:

| File | Description |
|------|-------------|
| [`create_employee.php`](examples/create_employee.php) | Create an employee with the HR module |
| [`create_invoice.php`](examples/create_invoice.php) | Full invoice lifecycle: create, finalize, send, PDF |
| [`process_payment.php`](examples/process_payment.php) | Payment intents, refunds, and subscriptions |
| [`webhook_handler.php`](examples/webhook_handler.php) | Receive and verify webhook events |
| [`authentication.php`](examples/authentication.php) | Login, token refresh, 2FA, sessions |
| [`loan_application.php`](examples/loan_application.php) | KYC, loan application, and repayments |
| [`einvoice_submit.php`](examples/einvoice_submit.php) | Normalize and submit e-invoices |
| [`crm_pipeline.php`](examples/crm_pipeline.php) | CRM: contacts, opportunities, pipeline |

## Development

### Running Tests

```bash
composer test
```

### Static Analysis

```bash
composer analyse
```

### Code Style

```bash
composer cs-fix
```

## Documentation

- [Getting Started Guide](docs/getting-started.md)
- [Module Reference](docs/modules.md)
- [Essabu API Documentation](https://docs.essabu.com)

## Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

1. Fork the repository
2. Create your feature branch: `git checkout -b feat/my-feature`
3. Write tests for your changes
4. Ensure all tests pass: `composer test`
5. Ensure static analysis passes: `composer analyse`
6. Commit using [Conventional Commits](https://www.conventionalcommits.org/): `git commit -m "feat: add new feature"`
7. Push and open a Pull Request

## Support

- [GitHub Issues](https://github.com/essabu/essabu-php/issues) - Bug reports and feature requests
- [Documentation](https://docs.essabu.com) - Full API documentation
- [Email](mailto:support@essabu.com) - Direct support

## License

The Essabu PHP SDK is open-sourced software licensed under the [MIT License](LICENSE).
