<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Essabu\Essabu;

$essabu = new Essabu('your-api-key', 'your-tenant-id');

// Create a customer
$customer = $essabu->trade->customers->create([
    'name' => 'Acme Corporation',
    'email' => 'contact@acme.com',
    'phone' => '+243 970 000 000',
    'address' => '123 Business Ave, Kinshasa',
    'type' => 'company',
]);
echo "Customer created: {$customer['id']}\n";

// Create an opportunity
$opportunity = $essabu->trade->opportunities->create([
    'customerId' => $customer['id'],
    'title' => 'Enterprise Software License',
    'value' => 50000.00,
    'currency' => 'USD',
    'stage' => 'qualification',
    'expectedCloseDate' => '2026-06-30',
]);
echo "Opportunity created: {$opportunity['id']}\n";

// Create a sales order
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

// Confirm the order
$essabu->trade->salesOrders->confirm($order['id']);
echo "Order confirmed.\n";

// Create a delivery
$delivery = $essabu->trade->deliveries->create([
    'salesOrderId' => $order['id'],
    'scheduledDate' => '2026-04-10',
]);
echo "Delivery scheduled: {$delivery['id']}\n";

// Ship the delivery
$essabu->trade->deliveries->ship($delivery['id'], [
    'carrier' => 'DHL',
    'trackingNumber' => 'DHL-123456',
]);
echo "Delivery shipped.\n";

// Mark opportunity as won
$essabu->trade->opportunities->won($opportunity['id']);
echo "Opportunity won!\n";

// Get sales report
$report = $essabu->trade->reports->salesSummary([
    'from' => '2026-01-01',
    'to' => '2026-03-31',
]);
echo "Sales summary: {$report['totalRevenue']} revenue\n";
