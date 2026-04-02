<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

final class RateLimitException extends EssabuException
{
    private ?int $retryAfter;

    /**
     * @param array<string, mixed> $context
     */
    public function __construct(string $message = 'Rate limit exceeded', ?int $retryAfter = null, array $context = [])
    {
        parent::__construct($message, 429, null, $context);
        $this->retryAfter = $retryAfter;
    }

    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }
}
