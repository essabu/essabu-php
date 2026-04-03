<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

final class ConflictException extends EssabuException
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(string $message = 'Conflict', array $context = [])
    {
        parent::__construct($message, 409, null, $context);
    }
}
