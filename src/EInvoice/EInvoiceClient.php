<?php

declare(strict_types=1);

namespace Essabu\EInvoice;

use Essabu\Common\HttpClient;
use Essabu\EInvoice\Api\InvoicesApi;
use Essabu\EInvoice\Api\SubmissionsApi;
use Essabu\EInvoice\Api\VerificationApi;
use Essabu\EInvoice\Api\ComplianceApi;
use Essabu\EInvoice\Api\StatisticsApi;

/**
 * E-Invoice module client.
 *
 * @property-read InvoicesApi $invoices
 * @property-read SubmissionsApi $submissions
 * @property-read VerificationApi $verification
 * @property-read ComplianceApi $compliance
 * @property-read StatisticsApi $statistics
 */
final class EInvoiceClient
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
            'invoices' => $this->resolve($name, InvoicesApi::class),
            'submissions' => $this->resolve($name, SubmissionsApi::class),
            'verification' => $this->resolve($name, VerificationApi::class),
            'compliance' => $this->resolve($name, ComplianceApi::class),
            'statistics' => $this->resolve($name, StatisticsApi::class),
            default => throw new \InvalidArgumentException("Unknown EInvoice API: {$name}"),
        };
    }

    private function resolve(string $name, string $class): object
    {
        return $this->cache[$name] ??= new $class($this->http);
    }
}
