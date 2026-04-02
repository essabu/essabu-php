<?php

declare(strict_types=1);

namespace Essabu\Payment;

use Essabu\Common\HttpClient;
use Essabu\Payment\Api\PaymentIntentsApi;
use Essabu\Payment\Api\PaymentAccountsApi;
use Essabu\Payment\Api\TransactionsApi;
use Essabu\Payment\Api\RefundsApi;
use Essabu\Payment\Api\SubscriptionsApi;
use Essabu\Payment\Api\SubscriptionPlansApi;
use Essabu\Payment\Api\FinancialAccountsApi;
use Essabu\Payment\Api\LoanProductsApi;
use Essabu\Payment\Api\LoanApplicationsApi;
use Essabu\Payment\Api\LoanRepaymentsApi;
use Essabu\Payment\Api\CollateralsApi;
use Essabu\Payment\Api\KycProfilesApi;
use Essabu\Payment\Api\KycDocumentsApi;
use Essabu\Payment\Api\ReportsApi;

/**
 * Payment module client.
 *
 * @property-read PaymentIntentsApi $paymentIntents
 * @property-read PaymentAccountsApi $paymentAccounts
 * @property-read TransactionsApi $transactions
 * @property-read RefundsApi $refunds
 * @property-read SubscriptionsApi $subscriptions
 * @property-read SubscriptionPlansApi $subscriptionPlans
 * @property-read FinancialAccountsApi $financialAccounts
 * @property-read LoanProductsApi $loanProducts
 * @property-read LoanApplicationsApi $loanApplications
 * @property-read LoanRepaymentsApi $loanRepayments
 * @property-read CollateralsApi $collaterals
 * @property-read KycProfilesApi $kycProfiles
 * @property-read KycDocumentsApi $kycDocuments
 * @property-read ReportsApi $reports
 */
final class PaymentClient
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
            'paymentIntents' => $this->resolve($name, PaymentIntentsApi::class),
            'paymentAccounts' => $this->resolve($name, PaymentAccountsApi::class),
            'transactions' => $this->resolve($name, TransactionsApi::class),
            'refunds' => $this->resolve($name, RefundsApi::class),
            'subscriptions' => $this->resolve($name, SubscriptionsApi::class),
            'subscriptionPlans' => $this->resolve($name, SubscriptionPlansApi::class),
            'financialAccounts' => $this->resolve($name, FinancialAccountsApi::class),
            'loanProducts' => $this->resolve($name, LoanProductsApi::class),
            'loanApplications' => $this->resolve($name, LoanApplicationsApi::class),
            'loanRepayments' => $this->resolve($name, LoanRepaymentsApi::class),
            'collaterals' => $this->resolve($name, CollateralsApi::class),
            'kycProfiles' => $this->resolve($name, KycProfilesApi::class),
            'kycDocuments' => $this->resolve($name, KycDocumentsApi::class),
            'reports' => $this->resolve($name, ReportsApi::class),
            default => throw new \InvalidArgumentException("Unknown Payment API: {$name}"),
        };
    }

    private function resolve(string $name, string $class): object
    {
        return $this->cache[$name] ??= new $class($this->http);
    }
}
