<?php

/**
 * Essabu PHP SDK - Process Payment Example
 *
 * This example demonstrates how to create a payment intent,
 * confirm it, handle failures, and process refunds using
 * the Payment module.
 *
 * Prerequisites:
 *   composer require essabu/essabu
 *
 * Usage:
 *   php examples/process_payment.php
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
    // 2. Create a payment intent
    $intent = $essabu->payment->paymentIntents->create([
        'amount'      => 15000,          // Amount in smallest currency unit (e.g. cents)
        'currency'    => 'USD',
        'description' => 'Invoice #INV-2026-0042 payment',
        'metadata'    => [
            'invoiceId'  => 'inv_abc123',
            'customerId' => 'cust_xyz789',
        ],
    ]);

    echo "Payment intent created!\n";
    echo "  ID:       {$intent['id']}\n";
    echo "  Amount:   {$intent['amount']} {$intent['currency']}\n";
    echo "  Status:   {$intent['status']}\n";

    // 3. Retrieve the payment intent to check status
    $retrieved = $essabu->payment->paymentIntents->get($intent['id']);
    echo "\nPayment intent status: {$retrieved['status']}\n";

    // 4. List recent transactions
    $transactions = $essabu->payment->transactions->list([
        'page' => 0,
        'size' => 10,
    ]);
    echo "\nRecent transactions:\n";
    foreach ($transactions as $tx) {
        echo "  {$tx['id']}: {$tx['amount']} {$tx['currency']} - {$tx['status']}\n";
    }

    // 5. Process a refund (partial refund example)
    $refund = $essabu->payment->refunds->create([
        'paymentIntentId' => $intent['id'],
        'amount'          => 5000,   // Partial refund of 50.00
        'reason'          => 'Customer requested partial refund',
    ]);

    echo "\nRefund processed!\n";
    echo "  Refund ID: {$refund['id']}\n";
    echo "  Amount:    {$refund['amount']} {$refund['currency']}\n";
    echo "  Status:    {$refund['status']}\n";

    // 6. Check subscription plans (bonus: recurring payments)
    $plans = $essabu->payment->subscriptionPlans->list(['page' => 0, 'size' => 5]);
    echo "\nAvailable subscription plans:\n";
    foreach ($plans as $plan) {
        echo "  - {$plan['name']}: {$plan['amount']} {$plan['currency']}/{$plan['interval']}\n";
    }

    // 7. Create a subscription for a customer
    $subscription = $essabu->payment->subscriptions->create([
        'customerId' => 'cust_xyz789',
        'planId'     => $plans[0]['id'] ?? 'plan_basic',
    ]);
    echo "\nSubscription created: {$subscription['id']}\n";
    echo "  Status:   {$subscription['status']}\n";
    echo "  Next billing: {$subscription['nextBillingDate']}\n";

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
