<?php

declare(strict_types=1);

namespace Essabu\Hr\Api;

use Essabu\Common\BaseApi;

/**
 * API client for managing HR report resources.
 */
final class ReportApi extends BaseApi
{
    private const BASE_PATH = '/api/hr/reports';

    /** @return array<string, mixed> */
    public function headcount(string $groupBy): array
    {
        return $this->http->get(self::BASE_PATH . '/headcount?groupBy=' . $groupBy);
    }

    /** @return array<string, mixed> */
    public function turnover(int $year): array
    {
        return $this->http->get(self::BASE_PATH . '/turnover?year=' . $year);
    }

    /** @return array<string, mixed> */
    public function absenteeism(string $month): array
    {
        return $this->http->get(self::BASE_PATH . '/absenteeism?month=' . $month);
    }

    /** @return array<string, mixed> */
    public function payrollCost(int $year): array
    {
        return $this->http->get(self::BASE_PATH . '/payroll-cost?year=' . $year);
    }

    /** @return array<string, mixed> */
    public function agePyramid(): array
    {
        return $this->http->get(self::BASE_PATH . '/age-pyramid');
    }

    /** @return array<string, mixed> */
    public function leaveUsage(): array
    {
        return $this->http->get(self::BASE_PATH . '/leave-usage');
    }

    /** @return array<string, mixed> */
    public function trainingCompliance(): array
    {
        return $this->http->get(self::BASE_PATH . '/training-compliance');
    }

    /** @return array<string, mixed> */
    public function dashboard(): array
    {
        return $this->http->get(self::BASE_PATH . '/dashboard');
    }
}
