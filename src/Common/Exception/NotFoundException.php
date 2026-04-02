<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

/**
 * Thrown on 404 Not Found responses.
 */
class NotFoundException extends EssabuException
{
    public function __construct(string $message = 'Resource not found')
    {
        parent::__construct($message, 404);
    }
}
