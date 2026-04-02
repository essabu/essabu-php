<?php

declare(strict_types=1);

namespace Essabu\Common;

use Essabu\EssabuConfig;

/**
 * Authentication interceptor for outgoing HTTP requests.
 *
 * @internal Not intended for direct use by SDK consumers.
 */
final class AuthInterceptor
{
    private readonly string $apiKey;
    private readonly string $tenantId;

    public function __construct(EssabuConfig $config)
    {
        $this->apiKey = $config->apiKey;
        $this->tenantId = $config->tenantId;
    }

    /** @return array<string, string> */
    public function getHeaders(): array
    {
        return [
            'Authorization' => "Bearer {$this->apiKey}",
            'X-Tenant-Id' => $this->tenantId,
            'Accept' => 'application/json',
        ];
    }
}
