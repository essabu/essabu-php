<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Essabu\Common\Exception\AuthenticationException;
use Essabu\Common\Exception\AuthorizationException;
use Essabu\Common\Exception\EssabuException;
use Essabu\Common\Exception\NotFoundException;
use Essabu\Common\Exception\RateLimitException;
use Essabu\Common\Exception\ValidationException;
use Essabu\Essabu;

$essabu = new Essabu('your-api-key', 'your-tenant-id');

try {
    $employee = $essabu->hr->employees->create([
        'firstName' => 'Jean',
        // Missing required fields...
    ]);
} catch (ValidationException $e) {
    echo "Validation failed: {$e->getMessage()}\n";
    foreach ($e->getErrors() as $field => $errors) {
        echo "  {$field}: " . implode(', ', (array) $errors) . "\n";
    }
} catch (AuthenticationException $e) {
    echo "Authentication error (401): {$e->getMessage()}\n";
    echo "Please check your API key.\n";
} catch (AuthorizationException $e) {
    echo "Authorization error (403): {$e->getMessage()}\n";
    echo "You don't have permission for this action.\n";
} catch (NotFoundException $e) {
    echo "Not found (404): {$e->getMessage()}\n";
} catch (RateLimitException $e) {
    echo "Rate limited (429): {$e->getMessage()}\n";
    if ($e->getRetryAfter() !== null) {
        echo "Retry after: {$e->getRetryAfter()} seconds\n";
    }
} catch (EssabuException $e) {
    echo "API error ({$e->getHttpStatusCode()}): {$e->getMessage()}\n";
    print_r($e->getContext());
}
