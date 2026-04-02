# Getting Started

## Installation

```bash
composer require essabu/essabu
```

## Authentication

The SDK uses API key authentication. Every request includes your API key and tenant ID as headers.

```php
use Essabu\Essabu;

$essabu = new Essabu('your-api-key', 'your-tenant-id');
```

You can obtain your API key from the Essabu dashboard under **Settings > API Keys**.

## Configuration Options

| Option | Default | Description |
|--------|---------|-------------|
| `baseUrl` | `https://api.essabu.com` | Base URL of the API |
| `timeout` | `30` | Request timeout in seconds |
| `retries` | `3` | Number of retries for 429/5xx errors |
| `apiVersion` | `v1` | API version |

```php
$essabu = new Essabu('api-key', 'tenant-id', [
    'baseUrl' => 'https://staging-api.essabu.com',
    'timeout' => 60,
]);
```

## Making Your First Request

```php
// List employees
$page = $essabu->hr->employees->list();
foreach ($page->items as $employee) {
    echo "{$employee['firstName']} {$employee['lastName']}\n";
}

// Create an employee
$employee = $essabu->hr->employees->create([
    'firstName' => 'Jean',
    'lastName' => 'Mukendi',
    'email' => 'jean@company.com',
]);
```

## Pagination

Use `PageRequest` to control pagination, filtering, and sorting:

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
```

## Error Handling

The SDK maps HTTP error codes to specific exception classes:

| HTTP Code | Exception |
|-----------|-----------|
| 401 | `AuthenticationException` |
| 403 | `AuthorizationException` |
| 404 | `NotFoundException` |
| 422 | `ValidationException` |
| 429 | `RateLimitException` |
| 5xx | `ServerException` |

All exceptions extend `EssabuException`.

## Next Steps

- See [Modules](modules.md) for complete API reference
- Check the `examples/` directory for real-world usage patterns
