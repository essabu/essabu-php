<?php

/**
 * Essabu PHP SDK - E-Invoice Submission Example
 *
 * This example demonstrates how to normalize an invoice into
 * the government-compliant e-invoice format, submit it for
 * validation, and track the submission status.
 *
 * Prerequisites:
 *   composer require essabu/essabu
 *
 * Usage:
 *   php examples/einvoice_submit.php
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
    // 2. Check compliance status first
    $compliance = $essabu->eInvoice->compliance->list(['page' => 0, 'size' => 5]);
    echo "Compliance checks:\n";
    foreach ($compliance as $check) {
        echo "  - {$check['rule']}: {$check['status']}\n";
    }

    // 3. Normalize invoice data into government-compliant format
    $normalized = $essabu->eInvoice->invoices->normalize([
        'seller' => [
            'name'     => 'Tech Solutions SARL',
            'taxId'    => 'CD-TIN-123456',
            'address'  => '45 Avenue de la Paix, Lubumbashi, CD',
        ],
        'buyer' => [
            'name'     => 'Global Corp Ltd',
            'taxId'    => 'CD-TIN-789012',
            'address'  => '12 Boulevard du Commerce, Kinshasa, CD',
        ],
        'invoiceNumber' => 'INV-2026-0042',
        'issueDate'     => '2026-03-26',
        'dueDate'       => '2026-04-26',
        'currency'      => 'CDF',
        'items'         => [
            [
                'description' => 'Software Development Services',
                'quantity'    => 1,
                'unitPrice'   => 5000000,
                'taxRate'     => 16.0,
                'taxCode'     => 'TVA_16',
            ],
            [
                'description' => 'Annual License Fee',
                'quantity'    => 1,
                'unitPrice'   => 1200000,
                'taxRate'     => 16.0,
                'taxCode'     => 'TVA_16',
            ],
        ],
    ]);

    echo "\nInvoice normalized!\n";
    echo "  Format:   {$normalized['format']}\n";
    echo "  Hash:     {$normalized['documentHash']}\n";
    echo "  Subtotal: {$normalized['subtotal']}\n";
    echo "  Tax:      {$normalized['taxTotal']}\n";
    echo "  Total:    {$normalized['total']}\n";

    // 4. Submit the normalized invoice to the government portal
    $submission = $essabu->eInvoice->submissions->submit(
        invoiceId: $normalized['id'],
        metadata: [
            'source'    => 'essabu-accounting',
            'invoiceId' => 'inv_abc123',
        ],
    );

    echo "\nE-Invoice submitted!\n";
    echo "  Submission ID: {$submission['id']}\n";
    echo "  Status:        {$submission['status']}\n";
    echo "  Submitted at:  {$submission['submittedAt']}\n";

    // 5. Poll for submission status
    echo "\nChecking submission status...\n";
    $maxAttempts = 5;
    $attempt = 0;

    while ($attempt < $maxAttempts) {
        $status = $essabu->eInvoice->submissions->checkStatus($submission['id']);
        echo "  Attempt " . ($attempt + 1) . ": {$status['status']}\n";

        if (in_array($status['status'], ['accepted', 'rejected'], true)) {
            break;
        }

        $attempt++;
        if ($attempt < $maxAttempts) {
            sleep(2); // Wait before polling again
        }
    }

    // 6. Handle the final status
    if ($status['status'] === 'accepted') {
        echo "\nE-Invoice accepted by government!\n";
        echo "  Government reference: {$status['governmentReference']}\n";
        echo "  Validated at: {$status['validatedAt']}\n";
    } elseif ($status['status'] === 'rejected') {
        echo "\nE-Invoice rejected!\n";
        echo "  Reason: {$status['rejectionReason']}\n";
        echo "  Fix the issues and resubmit.\n";
    } else {
        echo "\nSubmission still processing. Check again later.\n";
    }

    // 7. Verify an existing e-invoice
    $verification = $essabu->eInvoice->verification->list(['page' => 0, 'size' => 5]);
    echo "\nRecent verifications:\n";
    foreach ($verification as $v) {
        echo "  - Invoice {$v['invoiceNumber']}: {$v['status']}\n";
    }

    // 8. View e-invoice statistics
    $stats = $essabu->eInvoice->statistics->list(['page' => 0, 'size' => 5]);
    echo "\nE-Invoice statistics:\n";
    foreach ($stats as $stat) {
        echo "  - {$stat['period']}: {$stat['submitted']} submitted, {$stat['accepted']} accepted\n";
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
