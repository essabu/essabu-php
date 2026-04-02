<?php

declare(strict_types=1);

namespace Essabu\Hr;

use Essabu\Common\HttpClient;
use Essabu\Hr\Api\AttendanceApi;
use Essabu\Hr\Api\BenefitApi;
use Essabu\Hr\Api\ConfigApi;
use Essabu\Hr\Api\ContractApi;
use Essabu\Hr\Api\DepartmentApi;
use Essabu\Hr\Api\DisciplinaryApi;
use Essabu\Hr\Api\DocumentApi;
use Essabu\Hr\Api\EmployeeApi;
use Essabu\Hr\Api\ExpenseApi;
use Essabu\Hr\Api\HistoryApi;
use Essabu\Hr\Api\LeaveApi;
use Essabu\Hr\Api\LoanApi;
use Essabu\Hr\Api\OnboardingApi;
use Essabu\Hr\Api\PayrollApi;
use Essabu\Hr\Api\PerformanceApi;
use Essabu\Hr\Api\PositionApi;
use Essabu\Hr\Api\RecruitmentApi;
use Essabu\Hr\Api\ReportApi;
use Essabu\Hr\Api\ShiftApi;
use Essabu\Hr\Api\ShiftScheduleApi;
use Essabu\Hr\Api\SkillApi;
use Essabu\Hr\Api\TimesheetApi;
use Essabu\Hr\Api\TrainingApi;
use Essabu\Hr\Api\WebhookApi;

/**
 * HR module client.
 *
 * Access via: $essabu->hr->employees->create([...])
 *
 * @property-read EmployeeApi $employees
 * @property-read DepartmentApi $departments
 * @property-read PositionApi $positions
 * @property-read ContractApi $contracts
 * @property-read AttendanceApi $attendances
 * @property-read LeaveApi $leaves
 * @property-read ShiftApi $shifts
 * @property-read ShiftScheduleApi $shiftSchedules
 * @property-read TrainingApi $trainings
 * @property-read PayrollApi $payrolls
 * @property-read ExpenseApi $expenses
 * @property-read RecruitmentApi $recruitment
 * @property-read PerformanceApi $performance
 * @property-read OnboardingApi $onboarding
 * @property-read DocumentApi $documents
 * @property-read DisciplinaryApi $disciplinary
 * @property-read BenefitApi $benefits
 * @property-read LoanApi $loans
 * @property-read TimesheetApi $timesheets
 * @property-read SkillApi $skills
 * @property-read ReportApi $reports
 * @property-read WebhookApi $webhooks
 * @property-read ConfigApi $config
 * @property-read HistoryApi $history
 */
final class HrClient
{
    /** @var array<string, object> */
    private array $cache = [];

    public function __construct(
        private readonly HttpClient $http,
    ) {
    }

    public function __get(string $name): object
    {
        return match ($name) {
            'employees' => $this->resolve($name, EmployeeApi::class),
            'departments' => $this->resolve($name, DepartmentApi::class),
            'positions' => $this->resolve($name, PositionApi::class),
            'contracts' => $this->resolve($name, ContractApi::class),
            'attendances' => $this->resolve($name, AttendanceApi::class),
            'leaves' => $this->resolve($name, LeaveApi::class),
            'shifts' => $this->resolve($name, ShiftApi::class),
            'shiftSchedules' => $this->resolve($name, ShiftScheduleApi::class),
            'trainings' => $this->resolve($name, TrainingApi::class),
            'payrolls' => $this->resolve($name, PayrollApi::class),
            'expenses' => $this->resolve($name, ExpenseApi::class),
            'recruitment' => $this->resolve($name, RecruitmentApi::class),
            'performance' => $this->resolve($name, PerformanceApi::class),
            'onboarding' => $this->resolve($name, OnboardingApi::class),
            'documents' => $this->resolve($name, DocumentApi::class),
            'disciplinary' => $this->resolve($name, DisciplinaryApi::class),
            'benefits' => $this->resolve($name, BenefitApi::class),
            'loans' => $this->resolve($name, LoanApi::class),
            'timesheets' => $this->resolve($name, TimesheetApi::class),
            'skills' => $this->resolve($name, SkillApi::class),
            'reports' => $this->resolve($name, ReportApi::class),
            'webhooks' => $this->resolve($name, WebhookApi::class),
            'config' => $this->resolve($name, ConfigApi::class),
            'history' => $this->resolve($name, HistoryApi::class),
            default => throw new \InvalidArgumentException("Unknown HR API: {$name}"),
        };
    }

    private function resolve(string $name, string $class): object
    {
        return $this->cache[$name] ??= new $class($this->http);
    }
}
