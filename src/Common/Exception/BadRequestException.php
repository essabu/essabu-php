<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

final class BadRequestException extends EssabuException
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(string $message = 'Bad Request', array $context = [])
    {
        parent::__construct($message, 400, null, $context);
    }
}
