<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Essabu\Essabu;

/**
 * Initialize the SDK with your API key and tenant ID. This client will be used for
 * all payment, subscription, and lending operations in this example.
 */
$essabu = new Essabu('your-api-key', 'your-tenant-id');

/**
 * Create a payment intent by specifying the amount (in smallest currency unit), currency,
 * customer ID, a description, and the payment method. The intent starts in a
 * requires_confirmation status. Returns the intent with a generated id.
 * Throws ValidationException if the amount is zero or the currency is unsupported.
 */
$intent = $essabu->payment->paymentIntents->create([
    'amount' => 15000,
    'currency' => 'USD',
    'customerId' => 'cust-001',
    'description' => 'Invoice INV-2026-001 payment',
    'paymentMethod' => 'mobile_money',
]);
echo "Payment intent created: {$intent['id']}\n";

/**
 * Confirm the payment intent to authorize the charge. The status transitions to CONFIRMED
 * once the payment provider acknowledges the request. Returns the intent with its updated
 * status. Throws a 409 Conflict if the intent has already been confirmed or cancelled.
 */
$confirmed = $essabu->payment->paymentIntents->confirm($intent['id']);
echo "Payment confirmed: {$confirmed['status']}\n";

/**
 * Create a subscription for a customer by linking a plan ID and specifying a start date.
 * The subscription begins billing on the start date according to the plan's interval (monthly,
 * yearly). Returns the subscription with a generated id and status ACTIVE.
 * Throws NotFoundException if the planId is invalid.
 */
$subscription = $essabu->payment->subscriptions->create([
    'customerId' => 'cust-001',
    'planId' => 'plan-pro-monthly',
    'startDate' => '2026-04-01',
]);
echo "Subscription created: {$subscription['id']}\n";

/**
 * Create a refund for a previous transaction. Pass the transactionId, the amount to refund,
 * and a reason. The amount must not exceed the original transaction amount. Returns the
 * refund with a generated id and status PENDING. Throws ValidationException if the refund
 * amount exceeds the original transaction.
 */
$refund = $essabu->payment->refunds->create([
    'transactionId' => 'txn-001',
    'amount' => 5000,
    'reason' => 'Customer request',
]);
echo "Refund created: {$refund['id']}\n";

/**
 * Create a loan application for a customer. Pass the customerId, loan product ID, desired
 * amount, term in months, and purpose description. The application starts in PENDING status.
 * Returns the application with a generated id. Throws ValidationException if the amount
 * exceeds the loan product's maximum.
 */
$loan = $essabu->payment->loanApplications->create([
    'customerId' => 'cust-001',
    'loanProductId' => 'lp-001',
    'amount' => 100000,
    'term' => 12,
    'purpose' => 'Business expansion',
]);
echo "Loan application: {$loan['id']}\n";

/**
 * Approve the loan application, then disburse the funds to the customer's financial account.
 * Approval changes the status to APPROVED; disbursement changes it to DISBURSED and creates
 * a transaction record. Throws ValidationException if KYC verification is not complete.
 */
$essabu->payment->loanApplications->approve($loan['id']);
$essabu->payment->loanApplications->disburse($loan['id']);
echo "Loan disbursed.\n";

/**
 * Retrieve the balance of a financial account by its ID. Returns available and pending
 * amounts along with the currency. Throws NotFoundException if the account ID is invalid.
 */
$balance = $essabu->payment->financialAccounts->getBalance('acc-001');
echo "Account balance: {$balance['available']} {$balance['currency']}\n";
