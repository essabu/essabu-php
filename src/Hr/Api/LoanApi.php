<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

/**
 * API client for managing loan resources.
 */
final class LoanApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/loans';

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function create(array $request): array
    {
        return $this->http->post(self::BASE_PATH, $request);
    }

    /** @return array<int, array<string, mixed>> */
    public function listByEmployee(string $employeeId): array
    {
        return $this->http->get(self::BASE_PATH . '?employeeId=' . $employeeId);
    }

    /** @return array<string, mixed> */
    public function approve(string $id, string $approvedBy): array
    {
        return $this->http->put(self::BASE_PATH . '/' . $id . '/approve', ['approvedBy' => $approvedBy]);
    }

    /** @return array<int, array<string, mixed>> */
    public function getRepayments(string $loanId): array
    {
        return $this->http->get(self::BASE_PATH . '/' . $loanId . '/repayments');
    }

    /** @param array<string, mixed> $request @return array<string, mixed> */
    public function addRepayment(string $loanId, array $request): array
    {
        return $this->http->post(self::BASE_PATH . '/' . $loanId . '/repayments', $request);
    }
}
