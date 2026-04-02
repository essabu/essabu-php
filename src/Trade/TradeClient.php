<?php

declare(strict_types=1);

namespace Essabu\Trade;

use Essabu\Common\HttpClient;
use Essabu\Trade\Api\CustomersApi;
use Essabu\Trade\Api\ProductsApi;
use Essabu\Trade\Api\SalesOrdersApi;
use Essabu\Trade\Api\DeliveriesApi;
use Essabu\Trade\Api\ReceiptsApi;
use Essabu\Trade\Api\SuppliersApi;
use Essabu\Trade\Api\PurchaseOrdersApi;
use Essabu\Trade\Api\InventoryApi;
use Essabu\Trade\Api\StockApi;
use Essabu\Trade\Api\WarehousesApi;
use Essabu\Trade\Api\ContactsApi;
use Essabu\Trade\Api\OpportunitiesApi;
use Essabu\Trade\Api\ActivitiesApi;
use Essabu\Trade\Api\CampaignsApi;
use Essabu\Trade\Api\ContractsApi;
use Essabu\Trade\Api\DocumentsApi;

/**
 * Trade module client.
 *
 * Access via: $essabu->trade->customers->create([...])
 *
 * @property-read CustomersApi $customers
 * @property-read ProductsApi $products
 * @property-read SalesOrdersApi $salesOrders
 * @property-read DeliveriesApi $deliveries
 * @property-read ReceiptsApi $receipts
 * @property-read SuppliersApi $suppliers
 * @property-read PurchaseOrdersApi $purchaseOrders
 * @property-read InventoryApi $inventory
 * @property-read StockApi $stock
 * @property-read WarehousesApi $warehouses
 * @property-read ContactsApi $contacts
 * @property-read OpportunitiesApi $opportunities
 * @property-read ActivitiesApi $activities
 * @property-read CampaignsApi $campaigns
 * @property-read ContractsApi $contracts
 * @property-read DocumentsApi $documents
 */
final class TradeClient
{
    /** @var array<string, object> */
    private array $cache = [];

    public function __construct(
        private readonly HttpClient $http,
    ) {
    }

    public function __get(string $name): object
    {
        return match ($name) {
            'customers' => $this->resolve($name, CustomersApi::class),
            'products' => $this->resolve($name, ProductsApi::class),
            'salesOrders' => $this->resolve($name, SalesOrdersApi::class),
            'deliveries' => $this->resolve($name, DeliveriesApi::class),
            'receipts' => $this->resolve($name, ReceiptsApi::class),
            'suppliers' => $this->resolve($name, SuppliersApi::class),
            'purchaseOrders' => $this->resolve($name, PurchaseOrdersApi::class),
            'inventory' => $this->resolve($name, InventoryApi::class),
            'stock' => $this->resolve($name, StockApi::class),
            'warehouses' => $this->resolve($name, WarehousesApi::class),
            'contacts' => $this->resolve($name, ContactsApi::class),
            'opportunities' => $this->resolve($name, OpportunitiesApi::class),
            'activities' => $this->resolve($name, ActivitiesApi::class),
            'campaigns' => $this->resolve($name, CampaignsApi::class),
            'contracts' => $this->resolve($name, ContractsApi::class),
            'documents' => $this->resolve($name, DocumentsApi::class),
            default => throw new \InvalidArgumentException("Unknown Trade API: {$name}"),
        };
    }

    private function resolve(string $name, string $class): object
    {
        return $this->cache[$name] ??= new $class($this->http);
    }
}
