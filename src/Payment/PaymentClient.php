<?php

declare(strict_types=1);

namespace Essabu\Payment;

use Essabu\Common\HttpClient;
use Essabu\Payment\Api\CollateralApi;
use Essabu\Payment\Api\FinancialAccountApi;
use Essabu\Payment\Api\KycApi;
use Essabu\Payment\Api\LoanApplicationApi;
use Essabu\Payment\Api\LoanProductApi;
use Essabu\Payment\Api\PaymentIntentApi;
use Essabu\Payment\Api\RefundApi;
use Essabu\Payment\Api\ReportApi;
use Essabu\Payment\Api\SubscriptionApi;
use Essabu\Payment\Api\TransactionApi;

/**
 * @property-read PaymentIntentApi $paymentIntents
 * @property-read TransactionApi $transactions
 * @property-read RefundApi $refunds
 * @property-read SubscriptionApi $subscriptions
 * @property-read LoanProductApi $loanProducts
 * @property-read LoanApplicationApi $loanApplications
 * @property-read KycApi $kyc
 * @property-read CollateralApi $collaterals
 * @property-read FinancialAccountApi $financialAccounts
 * @property-read ReportApi $reports
 */
final class PaymentClient
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
            'paymentIntents' => new PaymentIntentApi($this->httpClient),
            'transactions' => new TransactionApi($this->httpClient),
            'refunds' => new RefundApi($this->httpClient),
            'subscriptions' => new SubscriptionApi($this->httpClient),
            'loanProducts' => new LoanProductApi($this->httpClient),
            'loanApplications' => new LoanApplicationApi($this->httpClient),
            'kyc' => new KycApi($this->httpClient),
            'collaterals' => new CollateralApi($this->httpClient),
            'financialAccounts' => new FinancialAccountApi($this->httpClient),
            'reports' => new ReportApi($this->httpClient),
            default => throw new \InvalidArgumentException("Unknown Payment API: {$name}"),
        };

        return $this->instances[$name];
    }
}
