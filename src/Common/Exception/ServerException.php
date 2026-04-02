<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

/**
 * Thrown on 5xx server errors after all retries are exhausted.
 */
class ServerException extends EssabuException
{
    public function __construct(string $message, int $statusCode = 500, ?\Throwable $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);
    }
}
