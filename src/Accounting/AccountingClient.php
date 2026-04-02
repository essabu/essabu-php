<?php

declare(strict_types=1);

namespace Essabu\Accounting;

use Essabu\Accounting\Api\AccountApi;
use Essabu\Accounting\Api\ConfigApi;
use Essabu\Accounting\Api\CouponApi;
use Essabu\Accounting\Api\CreditNoteApi;
use Essabu\Accounting\Api\CurrencyApi;
use Essabu\Accounting\Api\FiscalYearApi;
use Essabu\Accounting\Api\InsuranceApi;
use Essabu\Accounting\Api\InventoryApi;
use Essabu\Accounting\Api\InvoiceApi;
use Essabu\Accounting\Api\JournalApi;
use Essabu\Accounting\Api\PaymentApi;
use Essabu\Accounting\Api\QuoteApi;
use Essabu\Accounting\Api\ReportApi;
use Essabu\Accounting\Api\TaxRateApi;
use Essabu\Accounting\Api\WalletApi;
use Essabu\Accounting\Api\WebhookApi;
use Essabu\Common\HttpClient;

/**
 * @property-read AccountApi $accounts
 * @property-read InvoiceApi $invoices
 * @property-read PaymentApi $payments
 * @property-read QuoteApi $quotes
 * @property-read CreditNoteApi $creditNotes
 * @property-read JournalApi $journals
 * @property-read WalletApi $wallets
 * @property-read TaxRateApi $taxRates
 * @property-read CurrencyApi $currencies
 * @property-read FiscalYearApi $fiscalYears
 * @property-read InsuranceApi $insurances
 * @property-read InventoryApi $inventory
 * @property-read CouponApi $coupons
 * @property-read ReportApi $reports
 * @property-read ConfigApi $config
 * @property-read WebhookApi $webhooks
 */
final class AccountingClient
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
            'accounts' => new AccountApi($this->httpClient),
            'invoices' => new InvoiceApi($this->httpClient),
            'payments' => new PaymentApi($this->httpClient),
            'quotes' => new QuoteApi($this->httpClient),
            'creditNotes' => new CreditNoteApi($this->httpClient),
            'journals' => new JournalApi($this->httpClient),
            'wallets' => new WalletApi($this->httpClient),
            'taxRates' => new TaxRateApi($this->httpClient),
            'currencies' => new CurrencyApi($this->httpClient),
            'fiscalYears' => new FiscalYearApi($this->httpClient),
            'insurances' => new InsuranceApi($this->httpClient),
            'inventory' => new InventoryApi($this->httpClient),
            'coupons' => new CouponApi($this->httpClient),
            'reports' => new ReportApi($this->httpClient),
            'config' => new ConfigApi($this->httpClient),
            'webhooks' => new WebhookApi($this->httpClient),
            default => throw new \InvalidArgumentException("Unknown Accounting API: {$name}"),
        };

        return $this->instances[$name];
    }
}
