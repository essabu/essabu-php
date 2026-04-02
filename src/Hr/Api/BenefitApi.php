<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;

/**
 * API client for managing benefit resources.
 */
final class BenefitApi extends BaseApi
{
    private const PLANS_PATH = '/api/hr/benefit-plans';
    private const BENEFITS_PATH = '/api/hr/employee-benefits';

    // --- Plans ---

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function createPlan(array $request): array
    {
        return $this->http->post(self::PLANS_PATH, $request);
    }

    public function listPlans(?PageRequest $page = null): PageResponse
    {
        $data = $this->http->get($this->withPagination(self::PLANS_PATH, $page));
        return PageResponse::fromArray($data);
    }

    // --- Employee Benefits ---

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function enroll(array $request): array
    {
        return $this->http->post(self::BENEFITS_PATH, $request);
    }

    /** @return array<int, array<string, mixed>> */
    public function listByEmployee(string $employeeId): array
    {
        return $this->http->get(self::BENEFITS_PATH . '?employeeId=' . $employeeId);
    }

    /** @return array<string, mixed> */
    public function terminate(string $id): array
    {
        return $this->http->put(self::BENEFITS_PATH . '/' . $id . '/terminate');
    }
}
