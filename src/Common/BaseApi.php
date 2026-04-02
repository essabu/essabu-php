<?php

declare(strict_types=1);

namespace Essabu\Common;

use Essabu\Common\Model\PageRequest;

/**
 * Abstract base class shared by all API resource classes.
 */
abstract class BaseApi
{
    public function __construct(
        protected readonly HttpClient $http,
    ) {
    }

    /** Append pagination query parameters to a base path. */
    protected function withPagination(string $basePath, ?PageRequest $page): string
    {
        if ($page === null) {
            return $basePath;
        }
        $separator = str_contains($basePath, '?') ? '&' : '?';
        return $basePath . $separator . $page->toQueryString();
    }

    /** Append a single query parameter to a base path. */
    protected function withParam(string $basePath, string $key, mixed $value): string
    {
        if ($value === null) {
            return $basePath;
        }
        $separator = str_contains($basePath, '?') ? '&' : '?';
        return $basePath . $separator . $key . '=' . $value;
    }
}
