<?php

declare(strict_types=1);

namespace Essabu;

use Essabu\Accounting\AccountingClient;
use Essabu\Asset\AssetClient;
use Essabu\Common\HttpClient;
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
 *   $essabu = new Essabu('your-api-key', 'your-tenant-id');
 *
 *   // HR
 *   $employee = $essabu->hr->employees->create([
 *       'firstName' => 'Jean',
 *       'lastName' => 'Mukendi',
 *   ]);
 *
 *   // Accounting
 *   $invoice = $essabu->accounting->invoices->create([...]);
 *
 *   // Identity
 *   $token = $essabu->identity->auth->login([
 *       'email' => '...',
 *       'password' => '...',
 *   ]);
 *
 * @property-read HrClient $hr
 * @property-read AccountingClient $accounting
 * @property-read IdentityClient $identity
 * @property-read TradeClient $trade
 * @property-read PaymentClient $payment
 * @property-read EInvoiceClient $eInvoice
 * @property-read ProjectClient $project
 * @property-read AssetClient $asset
 */
final class Essabu
{
    private readonly HttpClient $http;
    private readonly EssabuConfig $config;

    /** @var array<string, object> */
    private array $cache = [];

    /**
     * Create a new Essabu client.
     *
     * @param string $apiKey  API key for authentication
     * @param string $tenantId  Tenant identifier
     * @param string $baseUrl  Base URL for the API gateway
     * @param float $connectTimeout  Connection timeout in seconds
     * @param float $readTimeout  Read timeout in seconds
     * @param int $maxRetries  Number of retries on 5xx errors
     */
    public function __construct(
        string $apiKey,
        string $tenantId,
        string $baseUrl = 'https://api.essabu.com',
        float $connectTimeout = 5.0,
        float $readTimeout = 30.0,
        int $maxRetries = 3,
    ) {
        $this->config = new EssabuConfig(
            apiKey: $apiKey,
            tenantId: $tenantId,
            baseUrl: $baseUrl,
            connectTimeout: $connectTimeout,
            readTimeout: $readTimeout,
            maxRetries: $maxRetries,
        );
        $this->http = new HttpClient($this->config);
    }

    /**
     * Create from an existing config object.
     */
    public static function fromConfig(EssabuConfig $config): self
    {
        return new self(
            apiKey: $config->apiKey,
            tenantId: $config->tenantId,
            baseUrl: $config->baseUrl,
            connectTimeout: $config->connectTimeout,
            readTimeout: $config->readTimeout,
            maxRetries: $config->maxRetries,
        );
    }

    public function __get(string $name): object
    {
        return match ($name) {
            'hr' => $this->resolve($name, HrClient::class),
            'accounting' => $this->resolve($name, AccountingClient::class),
            'identity' => $this->resolve($name, IdentityClient::class),
            'trade' => $this->resolve($name, TradeClient::class),
            'payment' => $this->resolve($name, PaymentClient::class),
            'eInvoice' => $this->resolve($name, EInvoiceClient::class),
            'project' => $this->resolve($name, ProjectClient::class),
            'asset' => $this->resolve($name, AssetClient::class),
            default => throw new \InvalidArgumentException("Unknown module: {$name}"),
        };
    }

    private function resolve(string $name, string $class): object
    {
        return $this->cache[$name] ??= new $class($this->http);
    }
}
