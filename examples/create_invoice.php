<?php

/**
 * Essabu PHP SDK - Create and Finalize Invoice Example
 *
 * This example demonstrates the full invoice lifecycle:
 * create a draft invoice, add line items, finalize it,
 * send it to the customer, and download the PDF.
 *
 * Prerequisites:
 *   composer require essabu/essabu
 *
 * Usage:
 *   php examples/create_invoice.php
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
    // 2. Create a draft invoice with line items
    $invoice = $essabu->accounting->invoices->create([
        'customerId' => 'cust_abc123',
        'companyId'  => 'comp_xyz789',
        'currency'   => 'USD',
        'dueDate'    => '2026-04-30',
        'notes'      => 'Thank you for your business!',
        'items'      => [
            [
                'description' => 'Web Development - March 2026',
                'quantity'    => 40,
                'unitPrice'   => 75.00,
                'taxRateId'   => 'tax_vat_16',
            ],
            [
                'description' => 'Hosting (Monthly)',
                'quantity'    => 1,
                'unitPrice'   => 49.99,
                'taxRateId'   => 'tax_vat_16',
            ],
        ],
    ]);

    echo "Draft invoice created!\n";
    echo "  Invoice ID:  {$invoice['id']}\n";
    echo "  Number:      {$invoice['number']}\n";
    echo "  Status:      {$invoice['status']}\n";
    echo "  Subtotal:    {$invoice['subtotal']} {$invoice['currency']}\n";
    echo "  Tax:         {$invoice['taxTotal']} {$invoice['currency']}\n";
    echo "  Total:       {$invoice['total']} {$invoice['currency']}\n";

    // 3. Finalize the invoice (locks it for editing, assigns number)
    $finalized = $essabu->accounting->invoices->finalize($invoice['id']);
    echo "\nInvoice finalized!\n";
    echo "  Status: {$finalized['status']}\n";
    echo "  Number: {$finalized['number']}\n";

    // 4. Send the invoice to the customer via email
    $sent = $essabu->accounting->invoices->send($invoice['id']);
    echo "\nInvoice sent to customer!\n";
    echo "  Sent at: {$sent['sentAt']}\n";

    // 5. Download the PDF
    $pdfContent = $essabu->accounting->invoices->downloadPdf($invoice['id']);
    $pdfPath = sys_get_temp_dir() . "/invoice_{$invoice['number']}.pdf";
    file_put_contents($pdfPath, $pdfContent);
    echo "\nPDF saved to: {$pdfPath}\n";

    // 6. Later, mark as paid when payment is received
    $paid = $essabu->accounting->invoices->markAsPaid($invoice['id']);
    echo "\nInvoice marked as paid!\n";
    echo "  Status: {$paid['status']}\n";

    // 7. Set up a recurring invoice
    $recurring = $essabu->accounting->invoices->createRecurring([
        'customerId'  => 'cust_abc123',
        'companyId'   => 'comp_xyz789',
        'currency'    => 'USD',
        'frequency'   => 'monthly',
        'startDate'   => '2026-05-01',
        'items'       => [
            [
                'description' => 'Hosting (Monthly)',
                'quantity'    => 1,
                'unitPrice'   => 49.99,
            ],
        ],
    ]);
    echo "\nRecurring invoice created: {$recurring['id']}\n";

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
