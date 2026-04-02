<?php

declare(strict_types=1);

namespace Essabu;

/**
 * Configuration for the Essabu SDK client.
 */
final class EssabuConfig
{
    public function __construct(
        /** API key for Bearer token authentication. */
        public readonly string $apiKey,
        /** Tenant identifier for multi-tenant isolation. */
        public readonly string $tenantId,
        /** Base URL for the Essabu API gateway. */
        public readonly string $baseUrl = 'https://api.essabu.com',
        /** Connection timeout in seconds. */
        public readonly float $connectTimeout = 5.0,
        /** Read timeout in seconds. */
        public readonly float $readTimeout = 30.0,
        /** Number of automatic retries on 5xx errors. */
        public readonly int $maxRetries = 3,
    ) {
        if (empty($this->apiKey)) {
            throw new \InvalidArgumentException('apiKey is required');
        }
        if (empty($this->tenantId)) {
            throw new \InvalidArgumentException('tenantId is required');
        }
    }
}
