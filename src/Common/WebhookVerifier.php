<?php

declare(strict_types=1);

namespace Essabu\Common;

/**
 * Verifies the signature of incoming webhook payloads.
 */
final class WebhookVerifier
{
    public function __construct(
        private readonly string $secret,
    ) {
    }

    public function verify(string $payload, string $signature): bool
    {
        $expected = 'sha256=' . hash_hmac('sha256', $payload, $this->secret);
        return hash_equals($expected, $signature);
    }
}
