# Trade Module Reference

The Trade module covers CRM (customers, contacts, opportunities, activities, campaigns), sales and purchasing, contracts, products, warehousing, stock management, deliveries, and receipts.

## Client Access

Access the Trade module through the main Essabu client. The `$trade` property exposes all trade sub-APIs as magic properties. You must first initialize the SDK with a valid API key. Returns the `TradeClient` instance.

```php
$essabu = new EssabuClient('your-api-key');
$trade = $essabu->trade;
```

## Available API Classes

| Class | Accessor | Description |
|-------|----------|-------------|
| `CustomerApi` | `$trade->customers` | Customer management |
| `ContactApi` | `$trade->contacts` | CRM contacts |
| `OpportunityApi` | `$trade->opportunities` | Sales opportunities |
| `ProductApi` | `$trade->products` | Product catalog |
| `SalesOrderApi` | `$trade->salesOrders` | Sales orders |
| `SupplierApi` | `$trade->suppliers` | Supplier management |
| `PurchaseOrderApi` | `$trade->purchaseOrders` | Purchase orders |
| `CampaignApi` | `$trade->campaigns` | Marketing campaigns |
| `ContractApi` | `$trade->contracts` | Contract management |
| `DocumentApi` | `$trade->documents` | Trade documents |
| `WarehouseApi` | `$trade->warehouses` | Warehouse management |
| `StockApi` | `$trade->stocks` | Stock management |
| `DeliveryApi` | `$trade->deliveries` | Delivery notes |
| `ReceiptApi` | `$trade->receipts` | Goods receipts |
| `ActivityApi` | `$trade->activities` | CRM activities |
| `ReportApi` | `$trade->reports` | Trade reports |

---

## Base CRUD Methods (inherited by all APIs)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `list(?PageRequest) -> PageResponse` | `GET /api/{basePath}` | List with pagination |
| `get(string $id) -> array` | `GET /api/{basePath}/{id}` | Get by ID |
| `create(array $data) -> array` | `POST /api/{basePath}` | Create resource |
| `update(string $id, array $data) -> array` | `PATCH /api/{basePath}/{id}` | Update resource |
| `delete(string $id) -> array` | `DELETE /api/{basePath}/{id}` | Delete resource |

## Standard CRUD-Only APIs

| Class | Base Path |
|-------|-----------|
| `CustomerApi` | `trade/customers` |
| `ContactApi` | `trade/contacts` |
| `OpportunityApi` | `trade/opportunities` |
| `ProductApi` | `trade/products` |
| `SalesOrderApi` | `trade/sales-orders` |
| `SupplierApi` | `trade/suppliers` |
| `PurchaseOrderApi` | `trade/purchase-orders` |
| `CampaignApi` | `trade/campaigns` |
| `ContractApi` | `trade/contracts` |
| `DocumentApi` | `trade/documents` |
| `WarehouseApi` | `trade/warehouses` |
| `StockApi` | `trade/stocks` |
| `DeliveryApi` | `trade/deliveries` |
| `ReceiptApi` | `trade/receipts` |
| `ActivityApi` | `trade/activities` |
| `ReportApi` | `trade/reports` |

Create a customer by providing `name`, `email`, and optionally `phone`. Create a sales order by linking it to a `customerId` and providing an array of line items with `productId`, `quantity`, and `unitPrice`. Use `stocks->list()` with a `PageRequest` to retrieve paginated stock levels. Create a delivery note tied to a `salesOrderId` with the items to ship. Returns each created resource as an associative array with a generated `id`. Throws `ValidationException` if required fields are missing, or a 422 error if stock is insufficient for a delivery.

```php
// Create a customer
$customer = $trade->customers->create([
    'name' => 'Acme Corp',
    'email' => 'contact@acme.com',
    'phone' => '+1234567890',
]);

// Create a sales order
$order = $trade->salesOrders->create([
    'customerId' => $customerId,
    'items' => [
        ['productId' => $productId, 'quantity' => 10, 'unitPrice' => 25.00],
    ],
]);

// Track stock
$stock = $trade->stocks->list(new PageRequest(page: 1, perPage: 50));

// Create a delivery note
$delivery = $trade->deliveries->create([
    'salesOrderId' => $orderId,
    'items' => [['productId' => $productId, 'quantity' => 10]],
]);
```

## Error Scenarios

| HTTP Status | Cause |
|-------------|-------|
| `400` | Invalid request data |
| `401` | Missing or expired authentication token |
| `403` | Insufficient permissions |
| `404` | Resource not found |
| `409` | Conflict (duplicate customer email, overlapping contract) |
| `422` | Business rule violation (insufficient stock) |
