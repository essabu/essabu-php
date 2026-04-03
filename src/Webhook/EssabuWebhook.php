<?php

declare(strict_types=1);

namespace Essabu\Webhook;

final class EssabuWebhook
{
    public static function verify(string $payload, string $signature, string $secret): bool
    {
        $computed = hash_hmac('sha256', $payload, $secret);

        return hash_equals($computed, $signature);
    }

    public static function parse(string $payload): WebhookEvent
    {
        /** @var array<string, mixed> $data */
        $data = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);

        return new WebhookEvent(
            id: (string) ($data['id'] ?? ''),
            type: (string) ($data['type'] ?? ''),
            data: is_array($data['data'] ?? null) ? $data['data'] : [],
            timestamp: (string) ($data['timestamp'] ?? ''),
        );
    }
}
