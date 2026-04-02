<?php

declare(strict_types=1);

namespace Essabu\Common\Exception;

class EssabuException extends \RuntimeException
{
    /** @var array<string, mixed> */
    protected array $context;

    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
        array $context = [],
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }

    public function getHttpStatusCode(): int
    {
        return $this->code;
    }
}
