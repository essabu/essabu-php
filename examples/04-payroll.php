<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Essabu\Essabu;

/**
 * Initialize the SDK with your API key and tenant ID. This client will be used for
 * all HR payroll and leave operations in this example.
 */
$essabu = new Essabu('your-api-key', 'your-tenant-id');

/**
 * Create a new payroll run by specifying the period (YYYY-MM format), department ID, and
 * a description. The payroll starts in DRAFT status. Returns the created payroll with a
 * generated id. Throws ValidationException if a payroll for this period and department
 * already exists.
 */
$payroll = $essabu->hr->payrolls->create([
    'period' => '2026-03',
    'departmentId' => 'dept-all',
    'description' => 'March 2026 Payroll',
]);
echo "Payroll created: {$payroll['id']}\n";

/**
 * Calculate the payroll to compute gross pay, deductions, taxes, and net pay for each
 * employee in the department. Returns the payroll with the computed totalAmount.
 * Throws ValidationException if employee salary data is missing or the payroll is not
 * in DRAFT status.
 */
$calculated = $essabu->hr->payrolls->calculate($payroll['id']);
echo "Payroll calculated. Total: {$calculated['totalAmount']}\n";

/**
 * Approve the calculated payroll to lock the amounts. Once approved, no further changes
 * can be made. Returns the payroll with the approvedBy field set to the current user.
 * Throws ValidationException if the payroll has not been calculated yet.
 */
$approved = $essabu->hr->payrolls->approve($payroll['id']);
echo "Payroll approved by: {$approved['approvedBy']}\n";

/**
 * Generate individual payslips for all employees included in the approved payroll run.
 * Returns a summary with the count of generated payslips. Throws ValidationException if
 * the payroll has not been approved yet.
 */
$payslips = $essabu->hr->payrolls->generatePayslips($payroll['id']);
echo "Payslips generated: {$payslips['count']} payslips\n";

/**
 * Create a leave request for an employee. Pass the employeeId, leave type (e.g., annual,
 * sick), startDate, endDate, and an optional reason. The request starts in PENDING status.
 * Returns the created leave with a generated id. Throws ValidationException if the
 * employee's leave balance is insufficient or dates overlap with existing leave.
 */
$leave = $essabu->hr->leaves->create([
    'employeeId' => 'emp-001',
    'type' => 'annual',
    'startDate' => '2026-04-14',
    'endDate' => '2026-04-18',
    'reason' => 'Family vacation',
]);
echo "Leave request created: {$leave['id']}\n";

/**
 * Approve a pending leave request by its ID. Changes the leave status to APPROVED and
 * deducts the days from the employee's balance. Returns the updated leave.
 * Throws ValidationException if the leave is not in PENDING status.
 */
$essabu->hr->leaves->approve($leave['id']);
echo "Leave approved.\n";

/**
 * Check the remaining leave balance for an employee by their ID. Returns an array with
 * the balance breakdown by leave type (annual, sick, etc.) including total, used, and
 * remaining days. Throws NotFoundException if the employee ID is invalid.
 */
$balance = $essabu->hr->leaves->getBalance('emp-001');
echo "Remaining leave: {$balance['remaining']} days\n";
