<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Essabu\Essabu;

$essabu = new Essabu('your-api-key', 'your-tenant-id');

// Create a payment intent
$intent = $essabu->payment->paymentIntents->create([
    'amount' => 15000,
    'currency' => 'USD',
    'customerId' => 'cust-001',
    'description' => 'Invoice INV-2026-001 payment',
    'paymentMethod' => 'mobile_money',
]);
echo "Payment intent created: {$intent['id']}\n";

// Confirm the payment
$confirmed = $essabu->payment->paymentIntents->confirm($intent['id']);
echo "Payment confirmed: {$confirmed['status']}\n";

// Create a subscription
$subscription = $essabu->payment->subscriptions->create([
    'customerId' => 'cust-001',
    'planId' => 'plan-pro-monthly',
    'startDate' => '2026-04-01',
]);
echo "Subscription created: {$subscription['id']}\n";

// Create a refund
$refund = $essabu->payment->refunds->create([
    'transactionId' => 'txn-001',
    'amount' => 5000,
    'reason' => 'Customer request',
]);
echo "Refund created: {$refund['id']}\n";

// Loan application
$loan = $essabu->payment->loanApplications->create([
    'customerId' => 'cust-001',
    'loanProductId' => 'lp-001',
    'amount' => 100000,
    'term' => 12,
    'purpose' => 'Business expansion',
]);
echo "Loan application: {$loan['id']}\n";

// Approve and disburse
$essabu->payment->loanApplications->approve($loan['id']);
$essabu->payment->loanApplications->disburse($loan['id']);
echo "Loan disbursed.\n";

// Check account balance
$balance = $essabu->payment->financialAccounts->getBalance('acc-001');
echo "Account balance: {$balance['available']} {$balance['currency']}\n";
