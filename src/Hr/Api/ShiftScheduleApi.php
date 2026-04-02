<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;

/**
 * API client for managing shift schedule resources.
 */
final class ShiftScheduleApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/shift-schedules';

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
    public function update(string $id, array $request): array
    {
        return $this->http->put(self::BASE_PATH . '/' . $id, $request);
    }

    public function delete(string $id): void
    {
        $this->http->delete(self::BASE_PATH . '/' . $id);
    }
}
