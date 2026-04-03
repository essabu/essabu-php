<?php

declare(strict_types=1);

namespace Essabu\Webhook;

final class WebhookEvent
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        public readonly string $id,
        public readonly string $type,
        public readonly array $data,
        public readonly string $timestamp,
    ) {
    }
}
