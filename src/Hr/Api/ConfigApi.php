<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

/**
 * API client for managing HR config resources.
 */
final class ConfigApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/config';

    /** @return array<string, mixed> */
    public function getConfig(): array
    {
        return $this->http->get(self::BASE_PATH);
    }

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function updateConfig(array $request): array
    {
        return $this->http->put(self::BASE_PATH, $request);
    }

    /** @return array<int, array<string, mixed>> */
    public function getLeavePolicies(): array
    {
        return $this->http->get(self::BASE_PATH . '/leave-policies');
    }

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function updateLeavePolicy(string $id, array $request): array
    {
        return $this->http->put(self::BASE_PATH . '/leave-policies/' . $id, $request);
    }

    /** @return array<int, array<string, mixed>> */
    public function getPayrollRules(): array
    {
        return $this->http->get(self::BASE_PATH . '/payroll-rules');
    }

    /** @return array<int, array<string, mixed>> */
    public function getHolidays(): array
    {
        return $this->http->get(self::BASE_PATH . '/holidays');
    }

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function addHoliday(array $request): array
    {
        return $this->http->post(self::BASE_PATH . '/holidays', $request);
    }
}
