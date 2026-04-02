<?php

declare(strict_types=1);

namespace Essabu\Common\Model;

/**
 * Generic paginated response wrapper.
 */
final class PageResponse
{
    /**
     * @param array<int, array<string, mixed>> $content
     */
    public function __construct(
        public readonly array $content = [],
        public readonly int $page = 0,
        public readonly int $size = 20,
        public readonly int $totalElements = 0,
        public readonly int $totalPages = 0,
        public readonly bool $first = true,
        public readonly bool $last = true,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            content: $data['content'] ?? [],
            page: (int) ($data['page'] ?? 0),
            size: (int) ($data['size'] ?? 20),
            totalElements: (int) ($data['totalElements'] ?? 0),
            totalPages: (int) ($data['totalPages'] ?? 0),
            first: (bool) ($data['first'] ?? true),
            last: (bool) ($data['last'] ?? true),
        );
    }

    public function hasContent(): bool
    {
        return count($this->content) > 0;
    }

    public function hasNext(): bool
    {
        return !$this->last;
    }

    public function hasPrevious(): bool
    {
        return !$this->first;
    }
}
