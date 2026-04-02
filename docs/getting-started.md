# Getting Started

## Installation

Install the Essabu PHP SDK via Composer. This is the only required dependency -- Guzzle and other transitive packages are pulled in automatically. Requires PHP 8.2 or higher.

```bash
composer require essabu/essabu
```

## Authentication

The SDK uses API key authentication. Every request includes your API key and tenant ID as headers.

Instantiate the main `Essabu` client by passing your API key and tenant ID. These two values are sent as headers on every HTTP request the SDK makes. The client is the single entry point for all module sub-APIs. Throws `AuthenticationException` if the key is invalid on the first request.

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

Override default configuration by passing an options array as the third constructor argument. Here the base URL is pointed to a staging environment and the timeout is doubled to 60 seconds. All options are optional; any omitted key keeps its default value.

```php
$essabu = new Essabu('api-key', 'tenant-id', [
    'baseUrl' => 'https://staging-api.essabu.com',
    'timeout' => 60,
]);
```

## Making Your First Request

List all employees using the HR module's `employees->list()` method, which returns a `PageResponse` containing paginated items. You can also create a new employee by passing an associative array with the required fields (`firstName`, `lastName`, `email`). Returns the created employee as an associative array with a generated `id`. Throws `ValidationException` if required fields are missing.

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

Use `PageRequest` to control pagination, filtering, and sorting. Pass the page number, items per page, an optional search string, sort field, sort direction, and key-value filters. The returned `PageResponse` contains `page`, `totalPages`, `totalItems`, and the `items` array. Throws `BadRequestException` if invalid filter keys are provided.

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
