<?php

declare(strict_types=1);

namespace Essabu;

use Essabu\Accounting\AccountingClient;
use Essabu\Asset\AssetClient;
use Essabu\Common\HttpClient;
use Essabu\Compliance\ComplianceClient;
use Essabu\EInvoice\EInvoiceClient;
use Essabu\Hr\HrClient;
use Essabu\Identity\IdentityClient;
use Essabu\Payment\PaymentClient;
use Essabu\Project\ProjectClient;
use Essabu\Trade\TradeClient;

/**
 * Main entry point for the Essabu SDK.
 *
 * Usage:
 *   $essabu = new Essabu('api-key', 'tenant-id');
 *   $essabu->hr->employees->create([...]);
 *   $essabu->accounting->invoices->list();
 *
 * @property-read HrClient $hr
 * @property-read AccountingClient $accounting
 * @property-read IdentityClient $identity
 * @property-read TradeClient $trade
 * @property-read PaymentClient $payment
 * @property-read EInvoiceClient $eInvoice
 * @property-read ProjectClient $project
 * @property-read AssetClient $asset
 * @property-read ComplianceClient $compliance
 */
final class Essabu
{
    private EssabuConfig $config;
    private HttpClient $httpClient;

    /** @var array<string, object> */
    private array $modules = [];

    /**
     * @param array<string, mixed> $options Optional config overrides (baseUrl, timeout, retries, apiVersion)
     */
    public function __construct(
        string $apiKey,
        string $tenantId,
        array $options = [],
    ) {
        $this->config = new EssabuConfig(
            apiKey: $apiKey,
            tenantId: $tenantId,
            baseUrl: (string) ($options['baseUrl'] ?? 'https://api.essabu.com'),
            timeout: (int) ($options['timeout'] ?? 30),
            connectTimeout: (int) ($options['connectTimeout'] ?? 10),
            retries: (int) ($options['retries'] ?? 3),
            apiVersion: (string) ($options['apiVersion'] ?? 'v1'),
        );

        $this->httpClient = new HttpClient($this->config);
    }

    public function __get(string $name): object
    {
        if (isset($this->modules[$name])) {
            return $this->modules[$name];
        }

        $this->modules[$name] = match ($name) {
            'hr' => new HrClient($this->httpClient),
            'accounting' => new AccountingClient($this->httpClient),
            'identity' => new IdentityClient($this->httpClient),
            'trade' => new TradeClient($this->httpClient),
            'payment' => new PaymentClient($this->httpClient),
            'eInvoice' => new EInvoiceClient($this->httpClient),
            'project' => new ProjectClient($this->httpClient),
            'asset' => new AssetClient($this->httpClient),
            'compliance' => new ComplianceClient($this->httpClient),
            default => throw new \InvalidArgumentException("Unknown module: {$name}"),
        };

        return $this->modules[$name];
    }

    public function getConfig(): EssabuConfig
    {
        return $this->config;
    }

    public function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }
}
