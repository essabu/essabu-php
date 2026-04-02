<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;

/**
 * API client for managing expense resources.
 */
final class ExpenseApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/expense-reports';

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function create(array $request): array
    {
        return $this->http->post(self::BASE_PATH, $request);
    }

    /** @return array<string, mixed> */
    public function get(string $id): array
    {
        return $this->http->get(self::BASE_PATH . '/' . $id);
    }

    public function list(?PageRequest $page = null): PageResponse
    {
        $data = $this->http->get($this->withPagination(self::BASE_PATH, $page));
        return PageResponse::fromArray($data);
    }

    /** @return array<string, mixed> */
    public function submit(string $id): array
    {
        return $this->http->put(self::BASE_PATH . '/' . $id . '/submit');
    }

    /** @return array<string, mixed> */
    public function approve(string $id, string $approvedBy): array
    {
        return $this->http->put(self::BASE_PATH . '/' . $id . '/approve', ['approvedBy' => $approvedBy]);
    }

    /** @return array<string, mixed> */
    public function reject(string $id): array
    {
        return $this->http->put(self::BASE_PATH . '/' . $id . '/reject');
    }
}
