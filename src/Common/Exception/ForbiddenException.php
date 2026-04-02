<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

/**
 * Thrown on 403 Forbidden responses.
 */
class ForbiddenException extends EssabuException
{
    public function __construct(string $message = 'Forbidden: insufficient permissions')
    {
        parent::__construct($message, 403);
    }
}
