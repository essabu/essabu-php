<?php

declare(strict_types=1);

namespace Essabu\Common\Model;

final class PageResponse
{
    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function __construct(
        public readonly array $items,
        public readonly int $totalItems,
        public readonly int $page,
        public readonly int $itemsPerPage,
        public readonly int $totalPages,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var array<int, array<string, mixed>> $items */
        $items = $data['hydra:member'] ?? $data['member'] ?? $data['items'] ?? $data['data'] ?? [];
        $totalItems = (int) ($data['hydra:totalItems'] ?? $data['totalItems'] ?? $data['total'] ?? count($items));
        $page = (int) ($data['page'] ?? 1);
        $itemsPerPage = (int) ($data['itemsPerPage'] ?? 30);
        $totalPages = $itemsPerPage > 0 ? (int) ceil($totalItems / $itemsPerPage) : 1;

        return new self(
            items: $items,
            totalItems: $totalItems,
            page: $page,
            itemsPerPage: $itemsPerPage,
            totalPages: $totalPages,
        );
    }

    public function hasNext(): bool
    {
        return $this->page < $this->totalPages;
    }
}
