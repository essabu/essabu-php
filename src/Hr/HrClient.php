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
use Essabu\Hr\Api\RecruitmentApi;
use Essabu\Hr\Api\ReportApi;
use Essabu\Hr\Api\ShiftApi;
use Essabu\Hr\Api\SkillApi;
use Essabu\Hr\Api\TimesheetApi;
use Essabu\Hr\Api\TrainingApi;
use Essabu\Hr\Api\WebhookApi;

/**
 * @property-read EmployeeApi $employees
 * @property-read ContractApi $contracts
 * @property-read LeaveApi $leaves
 * @property-read PayrollApi $payrolls
 * @property-read ShiftApi $shifts
 * @property-read DepartmentApi $departments
 * @property-read AttendanceApi $attendances
 * @property-read RecruitmentApi $recruitments
 * @property-read PerformanceApi $performances
 * @property-read TrainingApi $trainings
 * @property-read DocumentApi $documents
 * @property-read BenefitApi $benefits
 * @property-read LoanApi $loans
 * @property-read TimesheetApi $timesheets
 * @property-read SkillApi $skills
 * @property-read OnboardingApi $onboardings
 * @property-read ExpenseApi $expenses
 * @property-read DisciplinaryApi $disciplinary
 * @property-read ConfigApi $config
 * @property-read ReportApi $reports
 * @property-read WebhookApi $webhooks
 * @property-read HistoryApi $history
 */
final class HrClient
{
    /** @var array<string, object> */
    private array $instances = [];

    public function __construct(
        private readonly HttpClient $httpClient,
    ) {
    }

    public function __get(string $name): object
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        $this->instances[$name] = match ($name) {
            'employees' => new EmployeeApi($this->httpClient),
            'contracts' => new ContractApi($this->httpClient),
            'leaves' => new LeaveApi($this->httpClient),
            'payrolls' => new PayrollApi($this->httpClient),
            'shifts' => new ShiftApi($this->httpClient),
            'departments' => new DepartmentApi($this->httpClient),
            'attendances' => new AttendanceApi($this->httpClient),
            'recruitments' => new RecruitmentApi($this->httpClient),
            'performances' => new PerformanceApi($this->httpClient),
            'trainings' => new TrainingApi($this->httpClient),
            'documents' => new DocumentApi($this->httpClient),
            'benefits' => new BenefitApi($this->httpClient),
            'loans' => new LoanApi($this->httpClient),
            'timesheets' => new TimesheetApi($this->httpClient),
            'skills' => new SkillApi($this->httpClient),
            'onboardings' => new OnboardingApi($this->httpClient),
            'expenses' => new ExpenseApi($this->httpClient),
            'disciplinary' => new DisciplinaryApi($this->httpClient),
            'config' => new ConfigApi($this->httpClient),
            'reports' => new ReportApi($this->httpClient),
            'webhooks' => new WebhookApi($this->httpClient),
            'history' => new HistoryApi($this->httpClient),
            default => throw new \InvalidArgumentException("Unknown HR API: {$name}"),
        };

        return $this->instances[$name];
    }
}
