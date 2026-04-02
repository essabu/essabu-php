<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Essabu\Essabu;

/**
 * Initialize the Essabu SDK by passing your API key, tenant ID, and optional configuration.
 * The baseUrl defaults to https://api.essabu.com in production. The client instance is
 * the single entry point for all module sub-APIs. Throws AuthenticationException if the
 * API key is invalid on the first request.
 */
$essabu = new Essabu('your-api-key', 'your-tenant-id', [
    'baseUrl' => 'https://api.essabu.com',
]);

/**
 * Create a new employee in the HR module. Pass firstName, lastName, email, departmentId,
 * position, and hireDate as required fields. Returns the created employee as an associative
 * array including a generated UUID id. Throws ValidationException if required fields are
 * missing or if the email is already in use.
 */
$employee = $essabu->hr->employees->create([
    'firstName' => 'Jean',
    'lastName' => 'Mukendi',
    'email' => 'jean.mukendi@company.com',
    'departmentId' => 'dept-123',
    'position' => 'Software Engineer',
    'hireDate' => '2026-04-01',
]);

echo "Created employee: {$employee['id']}\n";

/**
 * List employees with pagination and search. Pass a PageRequest with page number,
 * itemsPerPage, and an optional search string to filter results. Returns a PageResponse
 * containing totalItems, totalPages, and the items array. Throws BadRequestException if
 * invalid filter parameters are provided.
 */
$page = $essabu->hr->employees->list(
    new \Essabu\Common\Model\PageRequest(page: 1, itemsPerPage: 20, search: 'Jean')
);

echo "Total employees: {$page->totalItems}\n";
foreach ($page->items as $emp) {
    echo "- {$emp['firstName']} {$emp['lastName']}\n";
}

/**
 * Retrieve a single employee by their UUID. Returns the full employee record as an
 * associative array. Throws NotFoundException if the ID does not match any employee.
 */
$emp = $essabu->hr->employees->get($employee['id']);
echo "Employee: {$emp['firstName']} {$emp['lastName']}\n";

/**
 * Update an existing employee by passing the employee ID and an array of fields to change.
 * Only the provided fields are updated; all others remain unchanged. Returns the updated
 * employee record. Throws NotFoundException if the ID is invalid, or ValidationException
 * if the new values violate business rules.
 */
$updated = $essabu->hr->employees->update($employee['id'], [
    'position' => 'Senior Software Engineer',
]);
echo "Updated position: {$updated['position']}\n";
