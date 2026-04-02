<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;

/**
 * API client for managing attendance resources.
 */
final class AttendanceApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/attendances';

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function record(array $request): array
    {
        return $this->http->post(self::BASE_PATH, $request);
    }

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function clockIn(array $request): array
    {
        return $this->http->post(self::BASE_PATH . '/clock-in', $request);
    }

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function clockOut(array $request): array
    {
        return $this->http->post(self::BASE_PATH . '/clock-out', $request);
    }

    public function list(?PageRequest $page = null): PageResponse
    {
        $data = $this->http->get($this->withPagination(self::BASE_PATH, $page));
        return PageResponse::fromArray($data);
    }

    /** @return array<string, mixed> */
    public function summary(string $employeeId, string $month): array
    {
        $path = $this->withParam(self::BASE_PATH . '/summary', 'employeeId', $employeeId);
        $path = $this->withParam($path, 'month', $month);
        return $this->http->get($path);
    }
}
