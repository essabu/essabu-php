<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

/**
 * Thrown on 400 / 422 validation errors. Carries field-level error details.
 */
class ValidationException extends EssabuException
{
    /** @param array<string, string> $fieldErrors */
    public function __construct(
        string $message,
        int $statusCode,
        public readonly array $fieldErrors = [],
    ) {
        parent::__construct($message, $statusCode);
    }
}
