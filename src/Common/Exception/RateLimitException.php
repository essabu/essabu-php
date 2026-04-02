<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

/**
 * Thrown on 429 Too Many Requests responses.
 */
class RateLimitException extends EssabuException
{
    public function __construct(
        string $message,
        int $statusCode = 429,
        public readonly float $retryAfter = 60.0,
    ) {
        parent::__construct($message, $statusCode);
    }
}
