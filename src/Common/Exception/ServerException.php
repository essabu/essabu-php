<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

final class ServerException extends EssabuException
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(string $message = 'Internal server error', int $code = 500, array $context = [])
    {
        parent::__construct($message, $code, null, $context);
    }
}
