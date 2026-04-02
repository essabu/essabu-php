<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Essabu\Essabu;

/**
 * Initialize the SDK with your API key and tenant ID. This client will be used for
 * all trade and CRM operations in this example.
 */
$essabu = new Essabu('your-api-key', 'your-tenant-id');

/**
 * Create a new customer in the CRM. Pass the name, email, phone, address, and type
 * (company or individual). Returns the created customer with a generated id.
 * Throws ValidationException if required fields are missing, or a 409 Conflict if a
 * customer with the same email already exists.
 */
$customer = $essabu->trade->customers->create([
    'name' => 'Acme Corporation',
    'email' => 'contact@acme.com',
    'phone' => '+243 970 000 000',
    'address' => '123 Business Ave, Kinshasa',
    'type' => 'company',
]);
echo "Customer created: {$customer['id']}\n";

/**
 * Create a sales opportunity linked to a customer. Pass customerId, title, estimated value,
 * currency, pipeline stage, and expected close date. Returns the created opportunity with a
 * generated id. Throws ValidationException if the customerId is invalid.
 */
$opportunity = $essabu->trade->opportunities->create([
    'customerId' => $customer['id'],
    'title' => 'Enterprise Software License',
    'value' => 50000.00,
    'currency' => 'USD',
    'stage' => 'qualification',
    'expectedCloseDate' => '2026-06-30',
]);
echo "Opportunity created: {$opportunity['id']}\n";

/**
 * Create a sales order linked to a customer and optionally an opportunity. Provide an array
 * of line items with productId, quantity, and unitPrice. Returns the created order in DRAFT
 * status with a generated id. Throws ValidationException if line items are empty.
 */
$order = $essabu->trade->salesOrders->create([
    'customerId' => $customer['id'],
    'opportunityId' => $opportunity['id'],
    'lines' => [
        [
            'productId' => 'prod-001',
            'quantity' => 10,
            'unitPrice' => 5000.00,
        ],
    ],
]);
echo "Sales order created: {$order['id']}\n";

/**
 * Confirm a draft sales order to lock its contents and begin fulfillment. Returns the
 * updated order with status changed to CONFIRMED. Throws ValidationException if the order
 * is not in DRAFT status or if stock is insufficient.
 */
$essabu->trade->salesOrders->confirm($order['id']);
echo "Order confirmed.\n";

/**
 * Create a delivery note linked to a confirmed sales order. Optionally specify a scheduled
 * delivery date. Returns the delivery with a generated id. Throws ValidationException if
 * the sales order has not been confirmed.
 */
$delivery = $essabu->trade->deliveries->create([
    'salesOrderId' => $order['id'],
    'scheduledDate' => '2026-04-10',
]);
echo "Delivery scheduled: {$delivery['id']}\n";

/**
 * Ship a delivery by providing the carrier name and tracking number. Updates the delivery
 * status to SHIPPED. Returns the updated delivery. Throws NotFoundException if the delivery
 * ID is invalid.
 */
$essabu->trade->deliveries->ship($delivery['id'], [
    'carrier' => 'DHL',
    'trackingNumber' => 'DHL-123456',
]);
echo "Delivery shipped.\n";

/**
 * Mark an opportunity as won. This transitions the opportunity stage to CLOSED_WON and
 * records the win date. Returns the updated opportunity. Throws ValidationException if the
 * opportunity is already closed.
 */
$essabu->trade->opportunities->won($opportunity['id']);
echo "Opportunity won!\n";

/**
 * Generate a sales summary report for a date range. Pass from and to dates in Y-m-d format.
 * Returns aggregated metrics including totalRevenue, totalOrders, and averageOrderValue.
 * Throws BadRequestException if the date range is invalid.
 */
$report = $essabu->trade->reports->salesSummary([
    'from' => '2026-01-01',
    'to' => '2026-03-31',
]);
echo "Sales summary: {$report['totalRevenue']} revenue\n";
