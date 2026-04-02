<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;

/**
 * API client for managing history / audit log resources.
 */
final class HistoryApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/history';

    public function listByEmployee(string $employeeId, ?PageRequest $page = null): PageResponse
    {
        $path = $this->withPagination(self::BASE_PATH . '?employeeId=' . $employeeId, $page);
        $data = $this->http->get($path);
        return PageResponse::fromArray($data);
    }

    public function listByEntityType(string $entityType, ?PageRequest $page = null): PageResponse
    {
        $path = $this->withPagination(self::BASE_PATH . '?entityType=' . $entityType, $page);
        $data = $this->http->get($path);
        return PageResponse::fromArray($data);
    }
}
