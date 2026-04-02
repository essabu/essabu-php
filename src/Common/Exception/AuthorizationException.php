<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

final class AuthorizationException extends EssabuException
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(string $message = 'Access denied', array $context = [])
    {
        parent::__construct($message, 403, null, $context);
    }
}
