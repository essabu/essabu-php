# Getting Started

## Installation

```bash
composer require essabu/essabu
```

## Creating a Client

The SDK provides a single entry point through the `Essabu` class:

```php
use Essabu\Essabu;

$essabu = new Essabu('your-api-key', 'your-tenant-id');
```

### Configuration Options

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `apiKey` | string | (required) | API key for Bearer token authentication |
| `tenantId` | string | (required) | Tenant identifier for multi-tenant isolation |
| `baseUrl` | string | `https://api.essabu.com` | Base URL of the API gateway |
| `connectTimeout` | float | `5.0` | Connection timeout in seconds |
| `readTimeout` | float | `30.0` | Read timeout in seconds |
| `maxRetries` | int | `3` | Automatic retries on 5xx errors with exponential backoff |

## Making API Calls

All API calls follow the pattern: `$essabu->{module}->{resource}->{method}()`

```php
// Create
$employee = $essabu->hr->employees->create([
    'firstName' => 'Jean',
    'lastName' => 'Mukendi',
]);

// Read
$employee = $essabu->hr->employees->get('employee-id');

// List with pagination
use Essabu\Common\Model\PageRequest;

$page = PageRequest::of(0, 20);
$result = $essabu->hr->employees->list($page);

// Update
$essabu->hr->employees->update('employee-id', [
    'firstName' => 'Jean-Pierre',
]);

// Delete
$essabu->hr->employees->delete('employee-id');
```

## Error Handling

The SDK throws typed exceptions for different HTTP error codes:

```php
use Essabu\Common\Exception\EssabuException;
use Essabu\Common\Exception\NotFoundException;
use Essabu\Common\Exception\ValidationException;
use Essabu\Common\Exception\UnauthorizedException;
use Essabu\Common\Exception\ForbiddenException;
use Essabu\Common\Exception\RateLimitException;
use Essabu\Common\Exception\ServerException;

try {
    $result = $essabu->hr->employees->get('id');
} catch (ValidationException $e) {
    // HTTP 400/422
    echo $e->getMessage();
    print_r($e->fieldErrors); // field-level validation errors
} catch (UnauthorizedException $e) {
    // HTTP 401 - invalid or expired API key
} catch (ForbiddenException $e) {
    // HTTP 403 - insufficient permissions
} catch (NotFoundException $e) {
    // HTTP 404
} catch (RateLimitException $e) {
    // HTTP 429
    echo $e->retryAfter; // seconds to wait
} catch (ServerException $e) {
    // HTTP 5xx after all retries exhausted
} catch (EssabuException $e) {
    // catch-all for any Essabu API error
    echo $e->statusCode;
}
```

## Webhooks

Verify incoming webhook signatures:

```php
use Essabu\Common\WebhookVerifier;

$verifier = new WebhookVerifier('your-webhook-secret');
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_ESSABU_SIGNATURE'] ?? '';

if ($verifier->verify($payload, $signature)) {
    // Process the webhook
    $data = json_decode($payload, true);
}
```
