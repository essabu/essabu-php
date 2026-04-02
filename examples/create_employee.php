<?php

/**
 * Essabu PHP SDK - Create Employee Example
 *
 * This example demonstrates how to create an employee using the HR module,
 * assign them to a department and position, and retrieve their details.
 *
 * Prerequisites:
 *   composer require essabu/essabu
 *
 * Usage:
 *   php examples/create_employee.php
 */

require __DIR__ . '/../vendor/autoload.php';

use Essabu\Essabu;
use Essabu\Common\Exception\ValidationException;
use Essabu\Common\Exception\EssabuException;

// 1. Initialize the client
$essabu = new Essabu(
    apiKey: getenv('ESSABU_API_KEY') ?: 'sk_test_your_api_key',
    tenantId: getenv('ESSABU_TENANT_ID') ?: 'tenant_your_id',
);

try {
    // 2. List existing departments to pick one
    $departments = $essabu->hr->departments->list(['page' => 0, 'size' => 5]);
    echo "Available departments:\n";
    foreach ($departments as $dept) {
        echo "  - {$dept['name']} (ID: {$dept['id']})\n";
    }

    // 3. Create a new employee
    $employee = $essabu->hr->employees->create([
        'firstName'    => 'Jean',
        'lastName'     => 'Mukendi',
        'email'        => 'jean.mukendi@example.com',
        'phone'        => '+243 812 345 678',
        'departmentId' => $departments[0]['id'] ?? 'dept_default',
        'positionId'   => 'pos_developer',
        'startDate'    => '2026-04-01',
        'salary'       => 2500.00,
        'currency'     => 'USD',
    ]);

    echo "\nEmployee created successfully!\n";
    echo "  ID:    {$employee['id']}\n";
    echo "  Name:  {$employee['firstName']} {$employee['lastName']}\n";
    echo "  Email: {$employee['email']}\n";

    // 4. Retrieve the employee to confirm
    $retrieved = $essabu->hr->employees->get($employee['id']);
    echo "\nRetrieved employee:\n";
    echo "  Status: {$retrieved['status']}\n";
    echo "  Department: {$retrieved['departmentName']}\n";

    // 5. Update the employee's phone number
    $updated = $essabu->hr->employees->update($employee['id'], [
        'phone' => '+243 998 765 432',
    ]);
    echo "\nPhone updated to: {$updated['phone']}\n";

    // 6. Get employee's leave balances
    $balances = $essabu->hr->employees->getLeaveBalances($employee['id']);
    echo "\nLeave balances:\n";
    foreach ($balances as $balance) {
        echo "  {$balance['leaveType']}: {$balance['remaining']} / {$balance['total']} days\n";
    }

} catch (ValidationException $e) {
    echo "Validation error: {$e->getMessage()}\n";
    foreach ($e->fieldErrors as $field => $error) {
        echo "  Field '{$field}': {$error}\n";
    }
    exit(1);
} catch (EssabuException $e) {
    echo "API error ({$e->statusCode}): {$e->getMessage()}\n";
    exit(1);
}
