<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Essabu\Essabu;

/**
 * Initialize the SDK with your API key and tenant ID. This client will be used for
 * all accounting operations in this example.
 */
$essabu = new Essabu('your-api-key', 'your-tenant-id');

/**
 * Create a new invoice for a customer. Pass the customerId, currency, dueDate, and an array
 * of line items each with description, quantity, unitPrice, and optional taxRateId. Optionally
 * include notes. The invoice starts in DRAFT status. Returns the created invoice with a
 * generated id and invoice number. Throws ValidationException if lines are empty or customerId
 * is invalid.
 */
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

/**
 * Finalize a draft invoice to lock its contents and generate the final amount. Once finalized,
 * the invoice can no longer be edited. Returns the invoice with status changed to FINALIZED.
 * Throws ValidationException if the invoice is already finalized or voided.
 */
$finalized = $essabu->accounting->invoices->finalize($invoice['id']);
echo "Invoice finalized: {$finalized['status']}\n";

/**
 * Send the finalized invoice to the customer via email. The recipient email is taken from the
 * customer record. Returns a confirmation. Throws ValidationException if the invoice has not
 * been finalized yet.
 */
$essabu->accounting->invoices->send($invoice['id']);
echo "Invoice sent to customer.\n";

/**
 * Mark the invoice as paid by providing the payment date and payment method. This updates the
 * invoice status to PAID and records the payment details. Returns the updated invoice.
 * Throws ValidationException if the invoice is already paid or voided.
 */
$essabu->accounting->invoices->markAsPaid($invoice['id'], [
    'paidAt' => '2026-04-15',
    'paymentMethod' => 'bank_transfer',
]);
echo "Invoice marked as paid.\n";

/**
 * Create a credit note linked to an existing invoice. Pass the invoiceId, a reason for the
 * credit, and an array of line items to credit. Returns the created credit note with a
 * generated id. Throws ValidationException if the credit amount exceeds the invoice total.
 */
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

/**
 * Generate a balance sheet report for a specific date. Pass the date as a string in Y-m-d
 * format. Returns the report data with assets, liabilities, and equity sections.
 * Throws BadRequestException if the date format is invalid.
 */
$balanceSheet = $essabu->accounting->reports->balanceSheet([
    'date' => '2026-03-31',
]);
echo "Balance sheet generated.\n";

/**
 * Generate a profit and loss (P&L) report for a date range. Pass from and to dates in Y-m-d
 * format. Returns revenue, expenses, and net income for the period.
 * Throws BadRequestException if the date range is invalid.
 */
$pnl = $essabu->accounting->reports->profitAndLoss([
    'from' => '2026-01-01',
    'to' => '2026-03-31',
]);
echo "P&L generated.\n";
