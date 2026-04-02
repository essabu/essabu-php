<?php

declare(strict_types=1);

namespace Essabu\Accounting;

use Essabu\Common\HttpClient;
use Essabu\Accounting\Api\Core\AccountApi;
use Essabu\Accounting\Api\Core\BalanceApi;
use Essabu\Accounting\Api\Core\ConfigApi;
use Essabu\Accounting\Api\Transactions\InvoiceApi;
use Essabu\Accounting\Api\Transactions\QuoteApi;
use Essabu\Accounting\Api\Transactions\CreditNoteApi;
use Essabu\Accounting\Api\Transactions\PaymentApi;
use Essabu\Accounting\Api\Transactions\PaymentTermApi;
use Essabu\Accounting\Api\Transactions\JournalApi;
use Essabu\Accounting\Api\Transactions\JournalEntryApi;
use Essabu\Accounting\Api\Finance\TaxRateApi;
use Essabu\Accounting\Api\Finance\CurrencyApi;
use Essabu\Accounting\Api\Finance\ExchangeRateApi;
use Essabu\Accounting\Api\Finance\ExchangeRateProviderApi;
use Essabu\Accounting\Api\Finance\FiscalYearApi;
use Essabu\Accounting\Api\Finance\PeriodApi;
use Essabu\Accounting\Api\Finance\ReportApi;
use Essabu\Accounting\Api\Finance\WalletApi;
use Essabu\Accounting\Api\Finance\WalletTransactionApi;
use Essabu\Accounting\Api\Commercial\InsurancePartnerApi;
use Essabu\Accounting\Api\Commercial\InsuranceContractApi;
use Essabu\Accounting\Api\Commercial\InsuranceClaimApi;
use Essabu\Accounting\Api\Commercial\PriceListApi;
use Essabu\Accounting\Api\Commercial\PriceListOverrideApi;
use Essabu\Accounting\Api\Inventory\SupplierApi;
use Essabu\Accounting\Api\Inventory\InventoryApi;
use Essabu\Accounting\Api\Inventory\PurchaseOrderApi;
use Essabu\Accounting\Api\Inventory\BatchApi;
use Essabu\Accounting\Api\Inventory\StockMovementApi;
use Essabu\Accounting\Api\Inventory\StockCountApi;
use Essabu\Accounting\Api\Inventory\StockLocationApi;
use Essabu\Accounting\Api\Inventory\WebhookApi;

/**
 * Accounting module client.
 *
 * Access via: $essabu->accounting->invoices->create([...])
 *
 * @property-read AccountApi $accounts
 * @property-read BalanceApi $balances
 * @property-read ConfigApi $config
 * @property-read InvoiceApi $invoices
 * @property-read QuoteApi $quotes
 * @property-read CreditNoteApi $creditNotes
 * @property-read PaymentApi $payments
 * @property-read PaymentTermApi $paymentTerms
 * @property-read JournalApi $journals
 * @property-read JournalEntryApi $journalEntries
 * @property-read TaxRateApi $taxRates
 * @property-read CurrencyApi $currencies
 * @property-read ExchangeRateApi $exchangeRates
 * @property-read ExchangeRateProviderApi $exchangeRateProviders
 * @property-read FiscalYearApi $fiscalYears
 * @property-read PeriodApi $periods
 * @property-read ReportApi $reports
 * @property-read WalletApi $wallets
 * @property-read WalletTransactionApi $walletTransactions
 * @property-read InsurancePartnerApi $insurancePartners
 * @property-read InsuranceContractApi $insuranceContracts
 * @property-read InsuranceClaimApi $insuranceClaims
 * @property-read PriceListApi $priceLists
 * @property-read PriceListOverrideApi $priceListOverrides
 * @property-read SupplierApi $suppliers
 * @property-read InventoryApi $inventory
 * @property-read PurchaseOrderApi $purchaseOrders
 * @property-read BatchApi $batches
 * @property-read StockMovementApi $stockMovements
 * @property-read StockCountApi $stockCounts
 * @property-read StockLocationApi $stockLocations
 * @property-read WebhookApi $webhooks
 */
final class AccountingClient
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
            'accounts' => $this->resolve($name, AccountApi::class),
            'balances' => $this->resolve($name, BalanceApi::class),
            'config' => $this->resolve($name, ConfigApi::class),
            'invoices' => $this->resolve($name, InvoiceApi::class),
            'quotes' => $this->resolve($name, QuoteApi::class),
            'creditNotes' => $this->resolve($name, CreditNoteApi::class),
            'payments' => $this->resolve($name, PaymentApi::class),
            'paymentTerms' => $this->resolve($name, PaymentTermApi::class),
            'journals' => $this->resolve($name, JournalApi::class),
            'journalEntries' => $this->resolve($name, JournalEntryApi::class),
            'taxRates' => $this->resolve($name, TaxRateApi::class),
            'currencies' => $this->resolve($name, CurrencyApi::class),
            'exchangeRates' => $this->resolve($name, ExchangeRateApi::class),
            'exchangeRateProviders' => $this->resolve($name, ExchangeRateProviderApi::class),
            'fiscalYears' => $this->resolve($name, FiscalYearApi::class),
            'periods' => $this->resolve($name, PeriodApi::class),
            'reports' => $this->resolve($name, ReportApi::class),
            'wallets' => $this->resolve($name, WalletApi::class),
            'walletTransactions' => $this->resolve($name, WalletTransactionApi::class),
            'insurancePartners' => $this->resolve($name, InsurancePartnerApi::class),
            'insuranceContracts' => $this->resolve($name, InsuranceContractApi::class),
            'insuranceClaims' => $this->resolve($name, InsuranceClaimApi::class),
            'priceLists' => $this->resolve($name, PriceListApi::class),
            'priceListOverrides' => $this->resolve($name, PriceListOverrideApi::class),
            'suppliers' => $this->resolve($name, SupplierApi::class),
            'inventory' => $this->resolve($name, InventoryApi::class),
            'purchaseOrders' => $this->resolve($name, PurchaseOrderApi::class),
            'batches' => $this->resolve($name, BatchApi::class),
            'stockMovements' => $this->resolve($name, StockMovementApi::class),
            'stockCounts' => $this->resolve($name, StockCountApi::class),
            'stockLocations' => $this->resolve($name, StockLocationApi::class),
            'webhooks' => $this->resolve($name, WebhookApi::class),
            default => throw new \InvalidArgumentException("Unknown Accounting API: {$name}"),
        };
    }

    private function resolve(string $name, string $class): object
    {
        return $this->cache[$name] ??= new $class($this->http);
    }
}
