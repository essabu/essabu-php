<?php

declare(strict_types=1);

namespace Essabu;

final class EssabuConfig
{
    public function __construct(
        public readonly string $apiKey,
        public readonly string $tenantId,
        public readonly string $baseUrl = 'https://api.essabu.com',
        public readonly int $timeout = 30,
        public readonly int $retries = 3,
        public readonly string $apiVersion = 'v1',
    ) {
    }

    public function buildUrl(string $path): string
    {
        return rtrim($this->baseUrl, '/') . '/api/' . $this->apiVersion . '/' . ltrim($path, '/');
    }
}
