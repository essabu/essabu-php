# HR Module Reference

The HR module provides comprehensive human resource management including employees, contracts, leave management, payroll, shifts, recruitment, performance reviews, and more.

## Client Access

```php
$essabu = new EssabuClient('your-api-key');
$hr = $essabu->hr;
```

All sub-APIs are accessed via magic properties on the `HrClient` instance.

## Available API Classes

| Class | Accessor | Description |
|-------|----------|-------------|
| `EmployeeApi` | `$hr->employees` | Employee records, termination, reinstatement |
| `ContractApi` | `$hr->contracts` | Employment contracts |
| `LeaveApi` | `$hr->leaves` | Leave requests with approval workflow |
| `PayrollApi` | `$hr->payrolls` | Payroll runs, calculation, payslips |
| `ShiftApi` | `$hr->shifts` | Shift management |
| `DepartmentApi` | `$hr->departments` | Organizational departments |
| `AttendanceApi` | `$hr->attendances` | Attendance tracking |
| `RecruitmentApi` | `$hr->recruitments` | Recruitment pipeline |
| `PerformanceApi` | `$hr->performances` | Performance reviews |
| `TrainingApi` | `$hr->trainings` | Training programs |
| `DocumentApi` | `$hr->documents` | Employee documents |
| `BenefitApi` | `$hr->benefits` | Benefit plans |
| `LoanApi` | `$hr->loans` | Employee loans |
| `TimesheetApi` | `$hr->timesheets` | Timesheet entries |
| `SkillApi` | `$hr->skills` | Skills management |
| `OnboardingApi` | `$hr->onboardings` | Onboarding checklists |
| `ExpenseApi` | `$hr->expenses` | Expense reports |
| `DisciplinaryApi` | `$hr->disciplinary` | Disciplinary actions |
| `ConfigApi` | `$hr->config` | HR configuration |
| `ReportApi` | `$hr->reports` | HR reports |
| `WebhookApi` | `$hr->webhooks` | Webhook management |
| `HistoryApi` | `$hr->history` | Employee history |

---

## Base CRUD Methods (inherited by all APIs)

Every API class extends `BaseApi` and inherits these methods:

| Method | Endpoint | Description |
|--------|----------|-------------|
| `list(?PageRequest) -> PageResponse` | `GET /api/{basePath}` | List with pagination |
| `get(string $id) -> array` | `GET /api/{basePath}/{id}` | Get by ID |
| `create(array $data) -> array` | `POST /api/{basePath}` | Create resource |
| `update(string $id, array $data) -> array` | `PATCH /api/{basePath}/{id}` | Update resource |
| `delete(string $id) -> array` | `DELETE /api/{basePath}/{id}` | Delete resource |

## EmployeeApi

Base path: `hr/employees`

Additional methods:

| Method | Endpoint | Description |
|--------|----------|-------------|
| `terminate(string $id, array $data) -> array` | `POST /api/hr/employees/{id}/terminate` | Terminate employee |
| `reinstate(string $id) -> array` | `POST /api/hr/employees/{id}/reinstate` | Reinstate employee |
| `getDocuments(string $id) -> array` | `GET /api/hr/employees/{id}/documents` | Get employee documents |

```php
// List employees
$employees = $hr->employees->list(new PageRequest(page: 1, perPage: 20));

// Create employee
$employee = $hr->employees->create([
    'firstName' => 'John',
    'lastName' => 'Doe',
    'email' => 'john@example.com',
]);

// Terminate an employee
$hr->employees->terminate($employeeId, ['terminationDate' => '2026-03-31', 'reason' => 'Resignation']);

// Reinstate
$hr->employees->reinstate($employeeId);
```

## LeaveApi

Base path: `hr/leaves`

Additional methods:

| Method | Endpoint | Description |
|--------|----------|-------------|
| `approve(string $id) -> array` | `POST /api/hr/leaves/{id}/approve` | Approve leave request |
| `reject(string $id, array $data) -> array` | `POST /api/hr/leaves/{id}/reject` | Reject leave request |
| `getBalance(string $employeeId) -> array` | `GET /api/hr/employees/{id}/leave-balance` | Get leave balance |

```php
$hr->leaves->approve($leaveId);
$hr->leaves->reject($leaveId, ['reason' => 'Insufficient notice']);
$balance = $hr->leaves->getBalance($employeeId);
```

## PayrollApi

Base path: `hr/payrolls`

Additional methods:

| Method | Endpoint | Description |
|--------|----------|-------------|
| `calculate(string $id) -> array` | `POST /api/hr/payrolls/{id}/calculate` | Calculate payroll |
| `approve(string $id) -> array` | `POST /api/hr/payrolls/{id}/approve` | Approve payroll |
| `generatePayslips(string $id) -> array` | `POST /api/hr/payrolls/{id}/payslips` | Generate payslips |

```php
$payroll = $hr->payrolls->create(['month' => '2026-03', 'departmentId' => $deptId]);
$hr->payrolls->calculate($payrollId);
$hr->payrolls->approve($payrollId);
$hr->payrolls->generatePayslips($payrollId);
```

## Standard CRUD-Only APIs

These APIs provide only the inherited CRUD operations:

| Class | Base Path |
|-------|-----------|
| `ContractApi` | `hr/contracts` |
| `ShiftApi` | `hr/shifts` |
| `DepartmentApi` | `hr/departments` |
| `AttendanceApi` | `hr/attendances` |
| `RecruitmentApi` | `hr/recruitments` |
| `PerformanceApi` | `hr/performances` |
| `TrainingApi` | `hr/trainings` |
| `DocumentApi` | `hr/documents` |
| `BenefitApi` | `hr/benefits` |
| `LoanApi` | `hr/loans` |
| `TimesheetApi` | `hr/timesheets` |
| `SkillApi` | `hr/skills` |
| `OnboardingApi` | `hr/onboardings` |
| `ExpenseApi` | `hr/expenses` |
| `DisciplinaryApi` | `hr/disciplinary` |
| `ConfigApi` | `hr/config` |
| `ReportApi` | `hr/reports` |
| `WebhookApi` | `hr/webhooks` |
| `HistoryApi` | `hr/history` |

## Error Scenarios

| HTTP Status | Cause |
|-------------|-------|
| `400` | Invalid request data (missing required fields) |
| `401` | Missing or expired authentication token |
| `403` | Insufficient permissions |
| `404` | Resource not found |
| `409` | Conflict (overlapping leave dates, duplicate contract) |
| `422` | Business rule violation (leave balance exceeded) |
