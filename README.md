# Essabu PHP SDK

Official PHP SDK for the Essabu platform. Provides unified access to HR, Accounting, Identity, Trade, Payment, E-Invoice, Project, and Asset modules with a Stripe-like fluent API.

## Requirements

- PHP 8.2+
- Composer

## Installation

```bash
composer require essabu/essabu
```

## Quick Start

```php
use Essabu\Essabu;

$essabu = new Essabu('your-api-key', 'your-tenant-id');

// HR - Create an employee
$employee = $essabu->hr->employees->create([
    'firstName' => 'Jean',
    'lastName' => 'Mukendi',
    'email' => 'jean@company.com',
]);

// Accounting - Create and send an invoice
$invoice = $essabu->accounting->invoices->create([
    'customerId' => 'cust-001',
    'lines' => [
        ['description' => 'Consulting', 'quantity' => 10, 'unitPrice' => 150.00],
    ],
]);
$essabu->accounting->invoices->finalize($invoice['id']);
$essabu->accounting->invoices->send($invoice['id']);

// Identity - Authenticate
$auth = $essabu->identity->auth->login([
    'email' => 'admin@company.com',
    'password' => 'password',
]);
```

## Configuration

```php
$essabu = new Essabu('api-key', 'tenant-id', [
    'baseUrl' => 'https://api.essabu.com',  // Custom API URL
    'timeout' => 30,                          // Request timeout in seconds
    'retries' => 3,                           // Retry count for 429/5xx
    'apiVersion' => 'v1',                     // API version
]);
```

## Modules

| Module | Access | Description |
|--------|--------|-------------|
| HR | `$essabu->hr` | Employees, contracts, payroll, leaves, attendance |
| Accounting | `$essabu->accounting` | Invoices, payments, journals, reports |
| Identity | `$essabu->identity` | Auth, users, roles, tenants |
| Trade | `$essabu->trade` | Customers, orders, products, CRM |
| Payment | `$essabu->payment` | Payment intents, subscriptions, loans |
| E-Invoice | `$essabu->eInvoice` | Electronic invoicing, compliance |
| Project | `$essabu->project` | Projects, tasks, milestones |
| Asset | `$essabu->asset` | Assets, vehicles, maintenance |

## CRUD Operations

Every API resource supports standard CRUD operations:

```php
// List with pagination
$page = $essabu->hr->employees->list(new PageRequest(
    page: 1,
    itemsPerPage: 20,
    search: 'Jean',
    orderBy: 'lastName',
    direction: 'asc',
));

// Get by ID
$employee = $essabu->hr->employees->get('emp-123');

// Create
$employee = $essabu->hr->employees->create([...]);

// Update (PATCH)
$employee = $essabu->hr->employees->update('emp-123', [...]);

// Delete
$essabu->hr->employees->delete('emp-123');
```

## Error Handling

```php
use Essabu\Common\Exception\AuthenticationException;
use Essabu\Common\Exception\ValidationException;
use Essabu\Common\Exception\NotFoundException;
use Essabu\Common\Exception\RateLimitException;
use Essabu\Common\Exception\EssabuException;

try {
    $essabu->hr->employees->create([...]);
} catch (ValidationException $e) {
    echo $e->getMessage();
    print_r($e->getErrors());
} catch (AuthenticationException $e) {
    // 401 - Invalid API key
} catch (NotFoundException $e) {
    // 404 - Resource not found
} catch (RateLimitException $e) {
    $retryAfter = $e->getRetryAfter(); // seconds
} catch (EssabuException $e) {
    echo $e->getHttpStatusCode();
    print_r($e->getContext());
}
```

## Testing

```bash
composer install
./vendor/bin/phpunit
```

## License

MIT - see [LICENSE](LICENSE).
