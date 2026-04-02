<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

/**
 * API client for managing disciplinary resources.
 */
final class DisciplinaryApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/disciplinary-actions';

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

    /** @return array<int, array<string, mixed>> */
    public function listByEmployee(string $employeeId): array
    {
        return $this->http->get(self::BASE_PATH . '?employeeId=' . $employeeId);
    }

    /** @return array<string, mixed> */
    public function revoke(string $id): array
    {
        return $this->http->put(self::BASE_PATH . '/' . $id . '/revoke');
    }
}
