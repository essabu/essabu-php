<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

final class ValidationException extends EssabuException
{
    /** @var array<string, string[]> */
    private array $errors;

    /**
     * @param array<string, string[]> $errors
     * @param array<string, mixed> $context
     */
    public function __construct(string $message = 'Validation failed', array $errors = [], array $context = [])
    {
        parent::__construct($message, 422, null, $context);
        $this->errors = $errors;
    }

    /**
     * @return array<string, string[]>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
