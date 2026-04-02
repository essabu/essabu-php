<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

final class AuthenticationException extends EssabuException
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(string $message = 'Authentication failed', array $context = [])
    {
        parent::__construct($message, 401, null, $context);
    }
}
