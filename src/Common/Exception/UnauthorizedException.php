<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

/**
 * Thrown on 401 Unauthorized responses.
 */
class UnauthorizedException extends EssabuException
{
    public function __construct(string $message = 'Unauthorized: invalid or expired API key')
    {
        parent::__construct($message, 401);
    }
}
