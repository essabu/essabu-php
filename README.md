# Essabu PHP SDK

[![Packagist Version](https://img.shields.io/packagist/v/essabu/essabu)](https://packagist.org/packages/essabu/essabu)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-777BB4)](https://php.net/)
[![PHPStan Level 5](https://img.shields.io/badge/PHPStan-Level%205-brightgreen)](https://phpstan.org/)

The official PHP SDK for the [Essabu](https://essabu.com) platform. It provides a single,
fluent client that covers every Essabu service: **HR**, **Accounting**, **Identity**, **Trade**,
**Payment**, **E-Invoice**, **Project**, and **Asset**. The SDK handles authentication,
pagination, error mapping, and automatic retries with exponential backoff so you can focus
on building your application. Whether you are working with Laravel, Symfony, or plain PHP,
the SDK integrates seamlessly through its straightforward constructor API. Module clients
and sub-APIs are accessed as properties, giving you a clean, readable call chain like
`$essabu->hr->employees->list()`. The comprehensive exception hierarchy maps every HTTP
error to a specific PHP exception, making error handling precise and predictable. Built on
Guzzle for HTTP and PSR-3 for logging, the SDK follows modern PHP conventions and supports
PHP 8.2+ with strict typing throughout.

---

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Configuration](#configuration)
- [Framework Integration](#framework-integration)
- [Modules](#modules)
  - [HR](#hr)
  - [Accounting](#accounting)
  - [Identity](#identity)
  - [Trade](#trade)
  - [Payment](#payment)
  - [E-Invoice](#e-invoice)
  - [Project](#project)
  - [Asset](#asset)
- [Pagination](#pagination)
- [Error Handling](#error-handling)
- [Retry Behavior](#retry-behavior)
- [Webhook Handling](#webhook-handling)
- [Examples](#examples)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

---

## Requirements

- PHP 8.2+
- Composer
- An Essabu API key ([Dashboard](https://app.essabu.com) > Settings > API Keys)

## Installation

Install the SDK via Composer. This pulls in Guzzle and all required dependencies automatically. Requires PHP 8.2 or higher.

```bash
composer require essabu/essabu
```

## Quick Start

The following example demonstrates how to initialize the SDK and perform basic operations across all eight modules. Pass your API key and tenant ID to the constructor. Each module is accessed as a property on the client, and each sub-API is accessed as a property on the module. All create methods return the created resource as an associative array with a generated `id`. Throws `AuthenticationException` if the API key is invalid, or `ValidationException` if required fields are missing.

```php
use Essabu\Essabu;
use Essabu\Common\Model\PageRequest;

$essabu = new Essabu('ess_live_xxxxxxxxxxxx', 'your-tenant-uuid');

// ----- HR -----
$employee = $essabu->hr->employees->create([
    'firstName' => 'Jean', 'lastName' => 'Mukendi', 'email' => 'jean@company.com',
]);

// ----- Accounting -----
$invoice = $essabu->accounting->invoices->create([
    'customerId' => 'cust-001',
    'lines' => [['description' => 'Consulting', 'quantity' => 10, 'unitPrice' => 150.00]],
]);
$essabu->accounting->invoices->finalize($invoice['id']);
$essabu->accounting->invoices->send($invoice['id']);

// ----- Identity -----
$tokens = $essabu->identity->auth->login([
    'email' => 'admin@company.com', 'password' => 'password',
]);

// ----- Trade -----
$customer = $essabu->trade->customers->create([
    'name' => 'Acme Corp', 'email' => 'contact@acme.com',
]);

// ----- Payment -----
$intent = $essabu->payment->paymentIntents->create([
    'amount' => 5000, 'currency' => 'USD', 'customerId' => $customer['id'],
]);
$essabu->payment->paymentIntents->confirm($intent['id']);

// ----- E-Invoice -----
$submission = $essabu->eInvoice->submissions->submit([
    'invoiceId' => $invoice['id'], 'buyerTin' => '123456789',
]);

// ----- Project -----
$project = $essabu->project->projects->create([
    'name' => 'Website Redesign', 'startDate' => '2026-04-01',
]);

// ----- Asset -----
$vehicle = $essabu->asset->vehicles->create([
    'make' => 'Toyota', 'model' => 'Hilux', 'year' => 2025, 'licensePlate' => 'ABC-1234',
]);
```

## Configuration

Customize the SDK behavior by passing an options array as the third constructor argument. You can override the base URL (useful for staging environments), adjust the request timeout, set the number of automatic retries for transient errors, and specify the API version. All options are optional and default to production-ready values.

```php
$essabu = new Essabu('api-key', 'tenant-id', [
    'baseUrl'    => 'https://api.essabu.com',  // default
    'timeout'    => 30,                          // seconds (default: 30)
    'retries'    => 3,                           // retry count for 429/5xx (default: 3)
    'apiVersion' => 'v1',                        // API version (default: v1)
]);
```

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `baseUrl` | `string` | `https://api.essabu.com` | API base URL |
| `timeout` | `int` | `30` | Request timeout in seconds |
| `retries` | `int` | `3` | Max retries for 429/5xx errors |
| `apiVersion` | `string` | `v1` | API version |

## Framework Integration

### Laravel

Register the Essabu client as a singleton in your `AppServiceProvider`. This ensures a single client instance is reused across all service injections. The API key and tenant ID are read from your `config/services.php` configuration.

```php
$this->app->singleton(Essabu::class, fn () => new Essabu(
    config('services.essabu.api_key'),
    config('services.essabu.tenant_id'),
    ['baseUrl' => config('services.essabu.base_url', 'https://api.essabu.com')],
));
```

Add the Essabu service credentials to `config/services.php`. The values are read from environment variables so that secrets are never committed to version control.

```php
'essabu' => [
    'api_key'   => env('ESSABU_API_KEY'),
    'tenant_id' => env('ESSABU_TENANT_ID'),
    'base_url'  => env('ESSABU_BASE_URL', 'https://api.essabu.com'),
],
```

Inject the `Essabu` client into any service or controller via constructor injection. Laravel resolves the singleton automatically. All module methods are available through the fluent property chain.

```php
class InvoiceService
{
    public function __construct(private Essabu $essabu) {}

    public function create(array $data): array
    {
        return $this->essabu->accounting->invoices->create($data);
    }
}
```

### Symfony

Register the Essabu client as a service in `config/services.yaml`. The API key and tenant ID are injected from environment variables. Optional parameters like `baseUrl` and `timeout` can be configured under the `$options` argument.

```yaml
services:
    Essabu\Essabu:
        arguments:
            $apiKey: '%env(ESSABU_API_KEY)%'
            $tenantId: '%env(ESSABU_TENANT_ID)%'
            $options:
                baseUrl: '%env(default::ESSABU_BASE_URL)%'
                timeout: 30
```

## Modules

### HR

`$essabu->hr` -- Comprehensive human resource management.

| Sub-API | Accessor | Highlights |
|---------|----------|------------|
| Employees | `->employees` | CRUD, terminate, reinstate |
| Contracts | `->contracts` | CRUD, renew, terminate |
| Leaves | `->leaves` | CRUD, approve/reject, balance |
| Payroll | `->payrolls` | CRUD, calculate, approve, payslips |
| Attendance | `->attendances` | CRUD, clockIn/clockOut |
| Recruitment | `->recruitments` | CRUD, shortlist, hire |
| Performance | `->performances` | CRUD, submit review |
| Training | `->trainings` | CRUD, enroll, complete |

Plus: Shifts, Departments, Documents, Benefits, Loans, Timesheets, Skills, Onboarding, Expenses, Disciplinary, Config, Reports, Webhooks, History.

[Full reference on the Wiki](https://github.com/essabu/essabu-php/wiki/HR-Module)

### Accounting

`$essabu->accounting` -- Complete financial management.

| Sub-API | Accessor | Highlights |
|---------|----------|------------|
| Invoices | `->invoices` | CRUD, finalize, send, void, PDF |
| Payments | `->payments` | CRUD, reconcile |
| Journals | `->journals` | CRUD, post, reverse |
| Quotes | `->quotes` | CRUD, accept, reject, convert to invoice |
| Reports | `->reports` | Balance sheet, P&L, trial balance, cash flow |
| Inventory | `->inventory` | CRUD, adjust |

Plus: Accounts, Wallets, Credit Notes, Tax Rates, Currencies, Fiscal Years, Insurance, Coupons, Config, Webhooks.

[Full reference on the Wiki](https://github.com/essabu/essabu-php/wiki/Accounting-Module)

### Identity

`$essabu->identity` -- Authentication and access control.

| Sub-API | Accessor | Highlights |
|---------|----------|------------|
| Auth | `->auth` | Login, register, 2FA, password reset |
| Users | `->users` | CRUD, assign/remove roles, activate/deactivate |
| Roles | `->roles` | CRUD, assign permissions |
| API Keys | `->apiKeys` | CRUD, rotate, revoke |
| Profile | `->profile` | me, updateMe, changePassword |

Plus: Permissions, Tenants, Companies, Branches, Sessions.

[Full reference on the Wiki](https://github.com/essabu/essabu-php/wiki/Identity-Module)

### Trade

`$essabu->trade` -- CRM, sales, purchasing, and inventory.

| Sub-API | Accessor | Highlights |
|---------|----------|------------|
| Customers | `->customers` | CRUD |
| Opportunities | `->opportunities` | CRUD, won, lost |
| Sales Orders | `->salesOrders` | CRUD, confirm, cancel, fulfill |
| Products | `->products` | CRUD |
| Stocks | `->stocks` | CRUD, transfer, adjust |
| Contracts | `->contracts` | CRUD, sign, renew |

Plus: Contacts, Suppliers, Purchase Orders, Campaigns, Documents, Warehouses, Deliveries, Receipts, Activities, Reports.

[Full reference on the Wiki](https://github.com/essabu/essabu-php/wiki/Trade-Module)

### Payment

`$essabu->payment` -- Payment processing and lending.

| Sub-API | Accessor | Highlights |
|---------|----------|------------|
| Payment Intents | `->paymentIntents` | CRUD, confirm, capture, cancel |
| Subscriptions | `->subscriptions` | CRUD, cancel, resume, change plan |
| Loan Applications | `->loanApplications` | CRUD, approve, reject, disburse |
| KYC | `->kyc` | CRUD, verify, submit documents |

Plus: Transactions, Refunds, Loan Products, Collaterals, Financial Accounts, Reports.

[Full reference on the Wiki](https://github.com/essabu/essabu-php/wiki/Payment-Module)

### E-Invoice

`$essabu->eInvoice` -- Electronic invoicing and tax compliance.

| Sub-API | Accessor | Highlights |
|---------|----------|------------|
| Invoices | `->invoices` | CRUD, sign, XML download |
| Submissions | `->submissions` | CRUD, submit, get status |
| Verifications | `->verifications` | CRUD, verify |
| Compliance | `->compliance` | Check, get rules |
| Statistics | `->statistics` | Overview |

[Full reference on the Wiki](https://github.com/essabu/essabu-php/wiki/EInvoice-Module)

### Project

`$essabu->project` -- Project and task management.

| Sub-API | Accessor | Highlights |
|---------|----------|------------|
| Projects | `->projects` | CRUD, archive, timeline |
| Tasks | `->tasks` | CRUD, assign, complete, log time |
| Milestones | `->milestones` | CRUD, complete |
| Task Comments | `->taskComments` | CRUD |
| Reports | `->reports` | Burndown |

Plus: Resource Allocations.

[Full reference on the Wiki](https://github.com/essabu/essabu-php/wiki/Project-Module)

### Asset

`$essabu->asset` -- Asset and fleet management.

| Sub-API | Accessor | Highlights |
|---------|----------|------------|
| Assets | `->assets` | CRUD, assign, dispose |
| Vehicles | `->vehicles` | CRUD |
| Maintenance Logs | `->maintenanceLogs` | CRUD, complete |
| Maintenance Schedules | `->maintenanceSchedules` | CRUD |
| Depreciation | `->depreciations` | Calculate, schedule |
| Fuel Logs | `->fuelLogs` | CRUD |
| Trip Logs | `->tripLogs` | CRUD |

[Full reference on the Wiki](https://github.com/essabu/essabu-php/wiki/Asset-Module)

## Pagination

Use `PageRequest` to control pagination, filtering, and sorting on any `list` method. Pass the page number, items per page, an optional search string, sort field, sort direction, and key-value filters. The returned `PageResponse` object contains `page`, `totalPages`, `totalItems`, and the `items` array. Throws `BadRequestException` if invalid filter keys are provided.

```php
use Essabu\Common\Model\PageRequest;

$request = new PageRequest(
    page: 2,
    itemsPerPage: 50,
    search: 'Kinshasa',
    orderBy: 'createdAt',
    direction: 'desc',
    filters: ['status' => 'active'],
);

$page = $essabu->hr->employees->list($request);

echo "Page {$page->page} of {$page->totalPages}\n";
echo "Total: {$page->totalItems}\n";

foreach ($page->items as $employee) {
    echo "{$employee['firstName']} {$employee['lastName']}\n";
}
```

To iterate through all pages of a paginated result set, increment the page counter in a loop until you reach `totalPages`. Each iteration fetches one page of results. This pattern works with any `list` method across all modules.

```php
$page = 1;
do {
    $result = $essabu->hr->employees->list(new PageRequest(page: $page, itemsPerPage: 50));
    foreach ($result->items as $employee) {
        process($employee);
    }
    $page++;
} while ($page <= $result->totalPages);
```

## Error Handling

The SDK maps every HTTP error status to a specific exception class. Catch them from most specific to most general. `ValidationException` provides field-level errors via `getErrors()`. `RateLimitException` includes a `getRetryAfter()` method indicating how many seconds to wait. The base `EssabuException` exposes `getHttpStatusCode()` and `getContext()` for debugging.

```php
use Essabu\Common\Exception\{
    AuthenticationException,
    AuthorizationException,
    ValidationException,
    NotFoundException,
    RateLimitException,
    ServerException,
    EssabuException,
};

try {
    $essabu->hr->employees->create([...]);
} catch (ValidationException $e) {
    // 422 - Business rule violation
    echo $e->getMessage();
    print_r($e->getErrors());  // field-level errors
} catch (AuthenticationException $e) {
    // 401 - Invalid API key
} catch (AuthorizationException $e) {
    // 403 - Insufficient permissions
} catch (NotFoundException $e) {
    // 404 - Resource not found
} catch (RateLimitException $e) {
    // 429 - Rate limit exceeded
    $retryAfter = $e->getRetryAfter(); // seconds
} catch (ServerException $e) {
    // 5xx - Server error (after retries)
} catch (EssabuException $e) {
    // Catch-all
    echo $e->getHttpStatusCode();
    print_r($e->getContext());
}
```

| Exception | HTTP Status | When |
|-----------|-------------|------|
| `BadRequestException` | 400 | Malformed request |
| `AuthenticationException` | 401 | Invalid or expired API key/token |
| `AuthorizationException` | 403 | Insufficient permissions |
| `NotFoundException` | 404 | Resource not found |
| `ValidationException` | 422 | Business rule violation |
| `RateLimitException` | 429 | Rate limit exceeded |
| `ServerException` | 5xx | Server-side error |

## Retry Behavior

The SDK automatically retries **429** (rate limit) and **5xx** (server error) responses with exponential backoff:

- **Max retries**: 3 (configurable via `retries` option)
- **Initial delay**: 500ms, doubling each attempt (500ms, 1s, 2s)
- **4xx errors** (except 429) are **never retried**

## Webhook Handling

### Laravel

Handle incoming Essabu webhooks in a Laravel controller. Verify the request signature using the `X-Essabu-Signature` header and your webhook secret. Parse the payload to extract the event type and data, then route to the appropriate handler. Returns a 401 response if the signature is invalid. The `EssabuWebhook::verify()` method uses HMAC-SHA256 to validate payload integrity.

```php
use Essabu\Webhook\EssabuWebhook;

class WebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Essabu-Signature');

        if (!EssabuWebhook::verify($payload, $signature, config('services.essabu.webhook_secret'))) {
            return response('Invalid signature', 401);
        }

        $event = EssabuWebhook::parse($payload);

        match ($event['type']) {
            'invoice.paid' => $this->handleInvoicePaid($event['data']),
            'employee.created' => $this->handleEmployeeCreated($event['data']),
            'payment_intent.confirmed' => $this->handlePaymentConfirmed($event['data']),
            default => logger()->info("Unhandled event: {$event['type']}"),
        };

        return response('OK', 200);
    }
}
```

### Symfony

Handle incoming Essabu webhooks in a Symfony controller. The same verification and parsing logic applies. Extract the payload from the `Request` object, verify the signature header against your webhook secret stored in an environment variable, then process the event. Returns a 401 `Response` if verification fails.

```php
#[Route('/webhooks/essabu', methods: ['POST'])]
public function handle(Request $request): Response
{
    $payload = $request->getContent();
    $signature = $request->headers->get('X-Essabu-Signature');

    if (!EssabuWebhook::verify($payload, $signature, $_ENV['ESSABU_WEBHOOK_SECRET'])) {
        return new Response('Invalid signature', 401);
    }

    $event = EssabuWebhook::parse($payload);
    // Process event...

    return new Response('OK', 200);
}
```

## Examples

| Example | File | Description |
|---------|------|-------------|
| Basic Usage | [`01-basic-usage.php`](examples/01-basic-usage.php) | Client init, basic CRUD |
| Authentication | [`02-authentication.php`](examples/02-authentication.php) | Login, tokens, 2FA |
| Invoicing | [`03-invoicing.php`](examples/03-invoicing.php) | Create, finalize, send invoices |
| Payroll | [`04-payroll.php`](examples/04-payroll.php) | Payroll runs and payslips |
| Trade / CRM | [`05-trade-crm.php`](examples/05-trade-crm.php) | Customers, opportunities, orders |
| Payments | [`06-payments.php`](examples/06-payments.php) | Payment intents, subscriptions |
| Error Handling | [`07-error-handling.php`](examples/07-error-handling.php) | Exception handling patterns |
| Project Management | [`08-project-management.php`](examples/08-project-management.php) | Projects, tasks, milestones |

See the full [examples/](examples/) directory and the [Wiki Examples page](https://github.com/essabu/essabu-php/wiki/Examples) for more.

## Testing

Run the test suite using PHPUnit. All tests are located in the `tests/` directory and cover unit, integration, and mock-based HTTP scenarios.

```bash
composer install
./vendor/bin/phpunit
```

Run PHPStan static analysis at level 5 to check for type errors, undefined methods, and other code quality issues across the `src/` directory.

```bash
./vendor/bin/phpstan analyse src --level 5
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feat/my-feature`)
3. Commit your changes (`git commit -m 'feat: add my feature'`)
4. Push to the branch (`git push origin feat/my-feature`)
5. Open a Pull Request

Please follow [Conventional Commits](https://www.conventionalcommits.org/) for commit messages.

## License

MIT -- see [LICENSE](LICENSE) for details.
