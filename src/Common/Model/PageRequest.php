<?php

declare(strict_types=1);

namespace Essabu\Common\Model;

final class PageRequest
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $itemsPerPage = 30,
        /** @var array<string, string> */
        public readonly array $filters = [],
        public readonly ?string $search = null,
        public readonly ?string $orderBy = null,
        public readonly string $direction = 'asc',
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toQuery(): array
    {
        $query = [
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
        ];

        if ($this->search !== null) {
            $query['search'] = $this->search;
        }

        if ($this->orderBy !== null) {
            $query['order[' . $this->orderBy . ']'] = $this->direction;
        }

        foreach ($this->filters as $key => $value) {
            $query[$key] = $value;
        }

        return $query;
    }
}
