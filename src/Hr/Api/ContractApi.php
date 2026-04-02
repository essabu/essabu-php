<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageResponse;

/**
 * API client for managing contract resources.
 */
final class ContractApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/contracts';

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
        return $this->http->get($this->withParam(self::BASE_PATH, 'employeeId', $employeeId));
    }

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function update(string $id, array $request): array
    {
        return $this->http->put(self::BASE_PATH . '/' . $id, $request);
    }

    /** @return array<string, mixed> */
    public function renew(string $id): array
    {
        return $this->http->put(self::BASE_PATH . '/' . $id . '/renew');
    }

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function terminate(string $id, array $request): array
    {
        return $this->http->put(self::BASE_PATH . '/' . $id . '/terminate', $request);
    }

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function createAmendment(string $contractId, array $request): array
    {
        return $this->http->post(self::BASE_PATH . '/' . $contractId . '/amendments', $request);
    }

    public function getExpiring(int $withinDays): PageResponse
    {
        $data = $this->http->get($this->withParam(self::BASE_PATH . '/expiring', 'withinDays', $withinDays));
        return PageResponse::fromArray($data);
    }
}
