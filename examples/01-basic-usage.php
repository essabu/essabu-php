<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Essabu\Essabu;

// Initialize the SDK
$essabu = new Essabu('your-api-key', 'your-tenant-id', [
    'baseUrl' => 'https://api.essabu.com',
]);

// Create an employee
$employee = $essabu->hr->employees->create([
    'firstName' => 'Jean',
    'lastName' => 'Mukendi',
    'email' => 'jean.mukendi@company.com',
    'departmentId' => 'dept-123',
    'position' => 'Software Engineer',
    'hireDate' => '2026-04-01',
]);

echo "Created employee: {$employee['id']}\n";

// List employees with pagination
$page = $essabu->hr->employees->list(
    new \Essabu\Common\Model\PageRequest(page: 1, itemsPerPage: 20, search: 'Jean')
);

echo "Total employees: {$page->totalItems}\n";
foreach ($page->items as $emp) {
    echo "- {$emp['firstName']} {$emp['lastName']}\n";
}

// Get a single employee
$emp = $essabu->hr->employees->get($employee['id']);
echo "Employee: {$emp['firstName']} {$emp['lastName']}\n";

// Update an employee
$updated = $essabu->hr->employees->update($employee['id'], [
    'position' => 'Senior Software Engineer',
]);
echo "Updated position: {$updated['position']}\n";
