<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;

/**
 * API client for managing employee resources.
 */
final class EmployeeApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/employees';

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

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function update(string $id, array $request): array
    {
        return $this->http->put(self::BASE_PATH . '/' . $id, $request);
    }

    public function delete(string $id): void
    {
        $this->http->delete(self::BASE_PATH . '/' . $id);
    }

    /** @return array<int, array<string, mixed>> */
    public function getLeaveBalances(string $employeeId): array
    {
        return $this->http->get(self::BASE_PATH . '/' . $employeeId . '/leave-balance');
    }

    /** @return array<int, array<string, mixed>> */
    public function getHistory(string $employeeId): array
    {
        return $this->http->get(self::BASE_PATH . '/' . $employeeId . '/history');
    }

    /** @return array<int, array<string, mixed>> */
    public function getDocuments(string $employeeId): array
    {
        return $this->http->get(self::BASE_PATH . '/' . $employeeId . '/documents');
    }

    /** @return array<int, array<string, mixed>> */
    public function getOrgTree(string $employeeId): array
    {
        return $this->http->get(self::BASE_PATH . '/' . $employeeId . '/org-tree');
    }

    /** @return array<string, mixed> */
    public function getOrgChart(): array
    {
        return $this->http->get(self::BASE_PATH . '/org-chart');
    }
}
