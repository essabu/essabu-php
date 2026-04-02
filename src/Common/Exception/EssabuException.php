<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

/**
 * Base exception for all Essabu API errors.
 */
class EssabuException extends \RuntimeException
{
    public function __construct(
        string $message,
        public readonly int $statusCode = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $statusCode, $previous);
    }
}
