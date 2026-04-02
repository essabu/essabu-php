<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

final class NotFoundException extends EssabuException
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(string $message = 'Resource not found', array $context = [])
    {
        parent::__construct($message, 404, null, $context);
    }
}
