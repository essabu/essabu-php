<?php

declare(strict_types=1);

namespace Essabu\Trade;

use Essabu\Common\HttpClient;
use Essabu\Trade\Api\ActivityApi;
use Essabu\Trade\Api\CampaignApi;
use Essabu\Trade\Api\ContactApi;
use Essabu\Trade\Api\ContractApi;
use Essabu\Trade\Api\CustomerApi;
use Essabu\Trade\Api\DeliveryApi;
use Essabu\Trade\Api\DocumentApi;
use Essabu\Trade\Api\OpportunityApi;
use Essabu\Trade\Api\ProductApi;
use Essabu\Trade\Api\PurchaseOrderApi;
use Essabu\Trade\Api\ReceiptApi;
use Essabu\Trade\Api\ReportApi;
use Essabu\Trade\Api\SalesOrderApi;
use Essabu\Trade\Api\StockApi;
use Essabu\Trade\Api\SupplierApi;
use Essabu\Trade\Api\WarehouseApi;

/**
 * @property-read CustomerApi $customers
 * @property-read ContactApi $contacts
 * @property-read OpportunityApi $opportunities
 * @property-read ProductApi $products
 * @property-read SalesOrderApi $salesOrders
 * @property-read SupplierApi $suppliers
 * @property-read PurchaseOrderApi $purchaseOrders
 * @property-read CampaignApi $campaigns
 * @property-read ContractApi $contracts
 * @property-read DocumentApi $documents
 * @property-read WarehouseApi $warehouses
 * @property-read StockApi $stocks
 * @property-read DeliveryApi $deliveries
 * @property-read ReceiptApi $receipts
 * @property-read ActivityApi $activities
 * @property-read ReportApi $reports
 */
final class TradeClient
{
    /** @var array<string, object> */
    private array $instances = [];

    public function __construct(
        private readonly HttpClient $httpClient,
    ) {
    }

    public function __get(string $name): object
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        $this->instances[$name] = match ($name) {
            'customers' => new CustomerApi($this->httpClient),
            'contacts' => new ContactApi($this->httpClient),
            'opportunities' => new OpportunityApi($this->httpClient),
            'products' => new ProductApi($this->httpClient),
            'salesOrders' => new SalesOrderApi($this->httpClient),
            'suppliers' => new SupplierApi($this->httpClient),
            'purchaseOrders' => new PurchaseOrderApi($this->httpClient),
            'campaigns' => new CampaignApi($this->httpClient),
            'contracts' => new ContractApi($this->httpClient),
            'documents' => new DocumentApi($this->httpClient),
            'warehouses' => new WarehouseApi($this->httpClient),
            'stocks' => new StockApi($this->httpClient),
            'deliveries' => new DeliveryApi($this->httpClient),
            'receipts' => new ReceiptApi($this->httpClient),
            'activities' => new ActivityApi($this->httpClient),
            'reports' => new ReportApi($this->httpClient),
            default => throw new \InvalidArgumentException("Unknown Trade API: {$name}"),
        };

        return $this->instances[$name];
    }
}
