<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;

/**
 * API client for managing leave resources.
 */
final class LeaveApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/leave-requests';

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function create(array $request): array
    {
        return $this->http->post(self::BASE_PATH, $request);
    }

    public function list(?PageRequest $page = null): PageResponse
    {
        $data = $this->http->get($this->withPagination(self::BASE_PATH, $page));
        return PageResponse::fromArray($data);
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

    /** @return array<string, mixed> */
    public function cancel(string $id): array
    {
        return $this->http->put(self::BASE_PATH . '/' . $id . '/cancel');
    }

    /** @return array<int, array<string, mixed>> */
    public function getBalances(string $employeeId): array
    {
        return $this->http->get($this->withParam('/api/hr/leave-balances', 'employeeId', $employeeId));
    }
}
