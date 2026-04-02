# Payment Module Reference

The Payment module handles payment processing, subscriptions, lending, KYC verification, and financial reporting.

## Client Access

Access the Payment module through the main Essabu client. The `$payment` property exposes all payment sub-APIs as magic properties. You must first initialize the SDK with a valid API key. Returns the `PaymentClient` instance.

```php
$essabu = new EssabuClient('your-api-key');
$payment = $essabu->payment;
```

## Available API Classes

| Class | Accessor | Description |
|-------|----------|-------------|
| `PaymentIntentApi` | `$payment->paymentIntents` | Payment intents with confirm/capture/cancel |
| `TransactionApi` | `$payment->transactions` | Transaction history |
| `RefundApi` | `$payment->refunds` | Refund operations |
| `SubscriptionApi` | `$payment->subscriptions` | Subscriptions with cancel/resume/change-plan |
| `LoanProductApi` | `$payment->loanProducts` | Loan product definitions |
| `LoanApplicationApi` | `$payment->loanApplications` | Loan applications |
| `KycApi` | `$payment->kyc` | KYC profiles and documents |
| `CollateralApi` | `$payment->collaterals` | Loan collaterals |
| `FinancialAccountApi` | `$payment->financialAccounts` | Financial accounts |
| `ReportApi` | `$payment->reports` | Lending reports |

---

## PaymentIntentApi

Base path: `payment/payment-intents`

Additional methods:

| Method | Endpoint | Description |
|--------|----------|-------------|
| `confirm(string $id) -> array` | `POST /api/payment/payment-intents/{id}/confirm` | Confirm intent |
| `capture(string $id, array $data) -> array` | `POST /api/payment/payment-intents/{id}/capture` | Capture payment |
| `cancel(string $id) -> array` | `POST /api/payment/payment-intents/{id}/cancel` | Cancel intent |

Create a payment intent by specifying the `amount` (in smallest currency unit), `currency`, and `customerId`. The intent starts in a `requires_confirmation` status. Call `confirm` to authorize the payment and `capture` to finalize it. Returns the intent with its current `status`. Throws `ValidationException` if the amount is invalid or currency is unsupported, or a 409 `Conflict` if the intent has already been confirmed.

```php
// Create and confirm a payment
$intent = $payment->paymentIntents->create([
    'amount' => 5000,
    'currency' => 'USD',
    'customerId' => $customerId,
]);
$payment->paymentIntents->confirm($intentId);
```

## SubscriptionApi

Base path: `payment/subscriptions`

Additional methods:

| Method | Endpoint | Description |
|--------|----------|-------------|
| `cancel(string $id, array $data) -> array` | `POST /api/payment/subscriptions/{id}/cancel` | Cancel subscription |
| `resume(string $id) -> array` | `POST /api/payment/subscriptions/{id}/resume` | Resume subscription |
| `changePlan(string $id, array $data) -> array` | `POST /api/payment/subscriptions/{id}/change-plan` | Change plan |

Create a subscription by providing a `planId` and `customerId`. Change the plan at any time by passing the new `planId`. Cancel with an optional `reason`, and resume a cancelled subscription before its period ends. Returns the subscription with its updated status. Throws `NotFoundException` if the plan or subscription ID is invalid, or `ValidationException` if the subscription has already expired.

```php
$sub = $payment->subscriptions->create([
    'planId' => $planId,
    'customerId' => $customerId,
]);
$payment->subscriptions->changePlan($subId, ['planId' => $newPlanId]);
$payment->subscriptions->cancel($subId, ['reason' => 'Downgrading']);
$payment->subscriptions->resume($subId);
```

## Standard CRUD-Only APIs

| Class | Base Path |
|-------|-----------|
| `TransactionApi` | `payment/transactions` |
| `RefundApi` | `payment/refunds` |
| `LoanProductApi` | `payment/loan-products` |
| `LoanApplicationApi` | `payment/loan-applications` |
| `KycApi` | `payment/kyc` |
| `CollateralApi` | `payment/collaterals` |
| `FinancialAccountApi` | `payment/financial-accounts` |
| `ReportApi` | `payment/reports` |

Create a refund by providing the `transactionId`, `amount` to refund, and a `reason`. Create a KYC profile by linking it to a `customerId` and specifying the `documentType` (e.g., `passport`, `national_id`). Returns the created resource with a generated `id`. Throws `ValidationException` if the refund amount exceeds the original transaction, or `NotFoundException` if the transaction ID is invalid.

```php
// Create a refund
$refund = $payment->refunds->create([
    'transactionId' => $txId,
    'amount' => 1000,
    'reason' => 'Customer request',
]);

// KYC profile
$kycProfile = $payment->kyc->create([
    'customerId' => $customerId,
    'documentType' => 'passport',
]);
```

## Error Scenarios

| HTTP Status | Cause |
|-------------|-------|
| `400` | Invalid request (bad amount, missing currency) |
| `401` | Missing or expired authentication token |
| `403` | Insufficient permissions |
| `404` | Intent, transaction, or subscription not found |
| `409` | Conflict (intent already confirmed) |
| `422` | Business rule violation (insufficient balance, KYC not verified) |
