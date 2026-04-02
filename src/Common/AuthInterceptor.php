<?php

declare(strict_types=1);

namespace Essabu\Common;

use Essabu\EssabuConfig;

final class AuthInterceptor
{
    public function __construct(
        private readonly EssabuConfig $config,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->config->apiKey,
            'X-Tenant-Id' => $this->config->tenantId,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}
