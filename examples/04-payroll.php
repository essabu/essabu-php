<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Essabu\Essabu;

$essabu = new Essabu('your-api-key', 'your-tenant-id');

// Create a payroll run
$payroll = $essabu->hr->payrolls->create([
    'period' => '2026-03',
    'departmentId' => 'dept-all',
    'description' => 'March 2026 Payroll',
]);
echo "Payroll created: {$payroll['id']}\n";

// Calculate payroll
$calculated = $essabu->hr->payrolls->calculate($payroll['id']);
echo "Payroll calculated. Total: {$calculated['totalAmount']}\n";

// Approve payroll
$approved = $essabu->hr->payrolls->approve($payroll['id']);
echo "Payroll approved by: {$approved['approvedBy']}\n";

// Generate payslips
$payslips = $essabu->hr->payrolls->generatePayslips($payroll['id']);
echo "Payslips generated: {$payslips['count']} payslips\n";

// Manage leaves
$leave = $essabu->hr->leaves->create([
    'employeeId' => 'emp-001',
    'type' => 'annual',
    'startDate' => '2026-04-14',
    'endDate' => '2026-04-18',
    'reason' => 'Family vacation',
]);
echo "Leave request created: {$leave['id']}\n";

$essabu->hr->leaves->approve($leave['id']);
echo "Leave approved.\n";

// Check leave balance
$balance = $essabu->hr->leaves->getBalance('emp-001');
echo "Remaining leave: {$balance['remaining']} days\n";
