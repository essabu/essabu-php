<?php

declare(strict_types=1);

namespace Essabu\Common\Model;

/**
 * Pagination parameters for list operations.
 */
final class PageRequest
{
    public function __construct(
        public readonly int $page = 0,
        public readonly int $size = 20,
        public readonly ?string $sort = null,
        public readonly ?string $direction = null,
    ) {
    }

    public static function of(int $page, int $size): self
    {
        return new self(page: $page, size: $size);
    }

    public static function first(): self
    {
        return new self();
    }

    public function toQueryString(): string
    {
        $parts = ["page={$this->page}", "size={$this->size}"];
        if ($this->sort !== null && $this->sort !== '') {
            $sortPart = $this->sort;
            if ($this->direction !== null && $this->direction !== '') {
                $sortPart .= ",{$this->direction}";
            }
            $parts[] = "sort={$sortPart}";
        }
        return implode('&', $parts);
    }
}
