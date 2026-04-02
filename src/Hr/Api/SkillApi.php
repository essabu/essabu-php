<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;

/**
 * API client for managing skill resources.
 */
final class SkillApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/skills';

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

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function assign(array $request): array
    {
        return $this->http->post('/api/hr/employee-skills', $request);
    }

    /** @return array<int, array<string, mixed>> */
    public function listByEmployee(string $employeeId): array
    {
        return $this->http->get('/api/hr/employee-skills?employeeId=' . $employeeId);
    }
}
