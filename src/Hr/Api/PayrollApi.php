<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;
use Essabu\Common\Model\PageRequest;
use Essabu\Common\Model\PageResponse;

/**
 * API client for managing payroll resources.
 */
final class PayrollApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/payrolls';

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

    /** @return array<string, mixed> */
    public function calculate(string $id): array
    {
        return $this->http->put(self::BASE_PATH . '/' . $id . '/calculate');
    }

    /** @return array<string, mixed> */
    public function approve(string $id, string $approvedBy): array
    {
        return $this->http->put(self::BASE_PATH . '/' . $id . '/approve', ['approvedBy' => $approvedBy]);
    }

    public function downloadPdf(string $id): string
    {
        return $this->http->getBytes(self::BASE_PATH . '/' . $id . '/pdf');
    }

    /** @return array<int, array<string, mixed>> */
    public function getPayslips(string $payrollId): array
    {
        return $this->http->get(self::BASE_PATH . '/' . $payrollId . '/payslips');
    }

    public function downloadPayslipPdf(string $payrollId, string $employeeId): string
    {
        return $this->http->getBytes(self::BASE_PATH . '/' . $payrollId . '/payslips/' . $employeeId . '/pdf');
    }

    /** @return array<string, mixed> */
    public function getYearToDate(string $employeeId, int $year): array
    {
        $path = $this->withParam('/api/hr/payroll-ytd', 'employeeId', $employeeId);
        $path = $this->withParam($path, 'year', $year);
        return $this->http->get($path);
    }
}
