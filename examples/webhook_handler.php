<?php

/**
 * Essabu PHP SDK - Webhook Handler Example
 *
 * This example demonstrates how to receive and verify incoming
 * webhook events from Essabu. Deploy this as an HTTP endpoint
 * (e.g., https://yourapp.com/webhooks/essabu).
 *
 * Essabu signs every webhook payload with HMAC-SHA256 using your
 * webhook secret. Always verify the signature before processing.
 *
 * Prerequisites:
 *   composer require essabu/essabu
 *
 * Usage:
 *   Deploy as a PHP endpoint behind your web server.
 */

require __DIR__ . '/../vendor/autoload.php';

use Essabu\Common\WebhookVerifier;

// 1. Configure your webhook secret (from Essabu dashboard)
$webhookSecret = getenv('ESSABU_WEBHOOK_SECRET') ?: 'whsec_your_webhook_secret';
$verifier = new WebhookVerifier($webhookSecret);

// 2. Read the raw request body and signature header
$payload   = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_ESSABU_SIGNATURE'] ?? '';

// 3. Verify the webhook signature
if (!$verifier->verify($payload, $signature)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid webhook signature']);
    exit;
}

// 4. Parse the event
$event = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);

$eventType = $event['type'] ?? 'unknown';
$eventData = $event['data'] ?? [];
$eventId   = $event['id'] ?? 'unknown';

echo "Received event: {$eventType} (ID: {$eventId})\n";

// 5. Handle different event types
switch ($eventType) {
    // -- HR Events --
    case 'hr.employee.created':
        $employee = $eventData;
        echo "New employee: {$employee['firstName']} {$employee['lastName']}\n";
        // TODO: Send welcome email, provision accounts, etc.
        break;

    case 'hr.employee.terminated':
        $employee = $eventData;
        echo "Employee terminated: {$employee['id']}\n";
        // TODO: Revoke access, archive data, etc.
        break;

    case 'hr.leave.approved':
        echo "Leave approved for employee: {$eventData['employeeId']}\n";
        // TODO: Update calendar, notify manager, etc.
        break;

    // -- Accounting Events --
    case 'accounting.invoice.finalized':
        $invoice = $eventData;
        echo "Invoice finalized: {$invoice['number']} - Total: {$invoice['total']}\n";
        // TODO: Send to customer, update ledger, etc.
        break;

    case 'accounting.invoice.paid':
        $invoice = $eventData;
        echo "Invoice paid: {$invoice['number']}\n";
        // TODO: Record payment, update customer balance, etc.
        break;

    case 'accounting.invoice.overdue':
        $invoice = $eventData;
        echo "Invoice overdue: {$invoice['number']}\n";
        // TODO: Send reminder, escalate, etc.
        break;

    // -- Payment Events --
    case 'payment.intent.succeeded':
        $intent = $eventData;
        echo "Payment succeeded: {$intent['id']} - {$intent['amount']} {$intent['currency']}\n";
        // TODO: Fulfill order, send receipt, etc.
        break;

    case 'payment.intent.failed':
        $intent = $eventData;
        echo "Payment failed: {$intent['id']}\n";
        // TODO: Notify customer, retry logic, etc.
        break;

    case 'payment.refund.created':
        $refund = $eventData;
        echo "Refund created: {$refund['id']} - {$refund['amount']}\n";
        // TODO: Update accounting, notify customer, etc.
        break;

    // -- E-Invoice Events --
    case 'einvoice.submission.accepted':
        echo "E-Invoice accepted: {$eventData['submissionId']}\n";
        // TODO: Update invoice status, notify accounting team, etc.
        break;

    case 'einvoice.submission.rejected':
        echo "E-Invoice rejected: {$eventData['submissionId']}\n";
        echo "  Reason: {$eventData['reason']}\n";
        // TODO: Fix and resubmit, alert team, etc.
        break;

    // -- Trade/CRM Events --
    case 'trade.opportunity.won':
        echo "Opportunity won: {$eventData['id']} - {$eventData['value']}\n";
        // TODO: Create invoice, notify sales team, etc.
        break;

    default:
        echo "Unhandled event type: {$eventType}\n";
        break;
}

// 6. Always respond with 200 to acknowledge receipt
http_response_code(200);
echo json_encode(['received' => true]);
