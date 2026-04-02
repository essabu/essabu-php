<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Essabu\Essabu;

$essabu = new Essabu('your-api-key', 'your-tenant-id');

// Create an invoice
$invoice = $essabu->accounting->invoices->create([
    'customerId' => 'cust-001',
    'currency' => 'USD',
    'dueDate' => '2026-04-30',
    'lines' => [
        [
            'description' => 'Web Development - March 2026',
            'quantity' => 160,
            'unitPrice' => 75.00,
            'taxRateId' => 'tax-standard',
        ],
        [
            'description' => 'Hosting - March 2026',
            'quantity' => 1,
            'unitPrice' => 99.00,
        ],
    ],
    'notes' => 'Payment due within 30 days.',
]);

echo "Invoice created: {$invoice['id']} - {$invoice['number']}\n";

// Finalize the invoice
$finalized = $essabu->accounting->invoices->finalize($invoice['id']);
echo "Invoice finalized: {$finalized['status']}\n";

// Send invoice via email
$essabu->accounting->invoices->send($invoice['id']);
echo "Invoice sent to customer.\n";

// Mark as paid
$essabu->accounting->invoices->markAsPaid($invoice['id'], [
    'paidAt' => '2026-04-15',
    'paymentMethod' => 'bank_transfer',
]);
echo "Invoice marked as paid.\n";

// Create a credit note
$creditNote = $essabu->accounting->creditNotes->create([
    'invoiceId' => $invoice['id'],
    'reason' => 'Partial refund for downtime',
    'lines' => [
        [
            'description' => 'Hosting credit for downtime',
            'quantity' => 1,
            'unitPrice' => 49.50,
        ],
    ],
]);
echo "Credit note created: {$creditNote['id']}\n";

// Get financial reports
$balanceSheet = $essabu->accounting->reports->balanceSheet([
    'date' => '2026-03-31',
]);
echo "Balance sheet generated.\n";

$pnl = $essabu->accounting->reports->profitAndLoss([
    'from' => '2026-01-01',
    'to' => '2026-03-31',
]);
echo "P&L generated.\n";
