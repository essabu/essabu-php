<?php

declare(strict_types=1);

namespace Essabu\EInvoice;

use Essabu\Common\HttpClient;
use Essabu\EInvoice\Api\ComplianceApi;
use Essabu\EInvoice\Api\InvoiceApi;
use Essabu\EInvoice\Api\StatisticsApi;
use Essabu\EInvoice\Api\SubmissionApi;
use Essabu\EInvoice\Api\VerificationApi;

/**
 * @property-read InvoiceApi $invoices
 * @property-read SubmissionApi $submissions
 * @property-read VerificationApi $verifications
 * @property-read ComplianceApi $compliance
 * @property-read StatisticsApi $statistics
 */
final class EInvoiceClient
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
            'invoices' => new InvoiceApi($this->httpClient),
            'submissions' => new SubmissionApi($this->httpClient),
            'verifications' => new VerificationApi($this->httpClient),
            'compliance' => new ComplianceApi($this->httpClient),
            'statistics' => new StatisticsApi($this->httpClient),
            default => throw new \InvalidArgumentException("Unknown EInvoice API: {$name}"),
        };

        return $this->instances[$name];
    }
}
