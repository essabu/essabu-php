# Modules Reference

## HR (`$essabu->hr`)

### Employees (`$essabu->hr->employees`)
- `create(array $data): array`
- `get(string $id): array`
- `list(?PageRequest $page = null): PageResponse`
- `update(string $id, array $data): array`
- `delete(string $id): void`
- `getLeaveBalances(string $employeeId): array`
- `getHistory(string $employeeId): array`
- `getDocuments(string $employeeId): array`
- `getOrgTree(string $employeeId): array`
- `getOrgChart(): array`

### Departments (`$essabu->hr->departments`)
- `create(array $data): array`
- `get(string $id): array`
- `list(?PageRequest $page = null): PageResponse`
- `update(string $id, array $data): array`
- `delete(string $id): void`

### Contracts (`$essabu->hr->contracts`)
- `create(array $data): array`
- `get(string $id): array`
- `listByEmployee(string $employeeId): array`
- `update(string $id, array $data): array`
- `renew(string $id): array`
- `terminate(string $id, array $data): array`
- `createAmendment(string $contractId, array $data): array`
- `getExpiring(int $withinDays): PageResponse`

### Payrolls (`$essabu->hr->payrolls`)
- `create(array $data): array`
- `get(string $id): array`
- `list(?PageRequest $page = null): PageResponse`
- `calculate(string $id): array`
- `approve(string $id, string $approvedBy): array`
- `downloadPdf(string $id): string`
- `getPayslips(string $payrollId): array`
- `downloadPayslipPdf(string $payrollId, string $employeeId): string`
- `getYearToDate(string $employeeId, int $year): array`

### Recruitment (`$essabu->hr->recruitment`)
- `createJobPosting(array $data): array`
- `getJobPosting(string $id): array`
- `listJobPostings(?PageRequest $page = null): PageResponse`
- `publishJobPosting(string $id): array`
- `closeJobPosting(string $id): array`
- `createApplication(array $data): array`
- `getApplication(string $id): array`
- `listApplications(string $jobPostingId, ?PageRequest $page = null): PageResponse`
- `advanceApplication(string $id): array`
- `rejectApplication(string $id): array`
- `hireApplication(string $id): array`
- `scheduleInterview(array $data): array`
- `getInterview(string $id): array`
- `completeInterview(string $id, array $data): array`

*Additional HR sub-modules: positions, attendances, leaves, shifts, shiftSchedules, trainings, expenses, performance, onboarding, documents, disciplinary, benefits, loans, timesheets, skills, reports, webhooks, config, history*

---

## Accounting (`$essabu->accounting`)

### Invoices (`$essabu->accounting->invoices`)
- `create(array $data): array`
- `get(string $invoiceId): array`
- `list(string $companyId, ?PageRequest $page = null): array`
- `update(string $invoiceId, array $data): array`
- `delete(string $invoiceId): void`
- `finalize(string $invoiceId): array`
- `send(string $invoiceId): array`
- `markAsPaid(string $invoiceId): array`
- `cancel(string $invoiceId): array`
- `downloadPdf(string $invoiceId): string`
- `createRecurring(array $data): array`
- `listRecurring(string $companyId, ?PageRequest $page = null): array`
- `updateRecurring(string $recurringId, array $data): array`
- `deleteRecurring(string $recurringId): void`
- `activateRecurring(string $recurringId): array`
- `deactivateRecurring(string $recurringId): array`

### Reports (`$essabu->accounting->reports`)
- `trialBalance(string $companyId, string $periodId): array`
- `balanceSheet(string $companyId, string $periodId): array`
- `incomeStatement(string $companyId, string $periodId): array`
- `generalLedger(string $companyId, string $periodId): array`

*Additional Accounting sub-modules: accounts, balances, config, quotes, creditNotes, payments, paymentTerms, journals, journalEntries, taxRates, currencies, exchangeRates, exchangeRateProviders, fiscalYears, periods, wallets, walletTransactions, insurancePartners, insuranceContracts, insuranceClaims, priceLists, priceListOverrides, suppliers, inventory, purchaseOrders, batches, stockMovements, stockCounts, stockLocations, webhooks*

---

## Identity (`$essabu->identity`)

### Auth (`$essabu->identity->auth`)
- `login(array $credentials): array`
- `register(array $data): array`
- `refresh(string $refreshToken): array`
- `logout(): array`
- `verifyEmail(string $token): array`
- `resetPassword(string $email): array`
- `enable2fa(): array`

### CRUD Resources
Users, Profiles, Companies, Tenants, Roles, Permissions, Branches all support:
- `list(array $params = []): array`
- `get(string $id): array`
- `create(array $data): array`
- `update(string $id, array $data): array`
- `delete(string $id): void`

### ApiKeys (`$essabu->identity->apiKeys`)
- Standard CRUD + `revoke(string $apiKeyId): array`

### Sessions (`$essabu->identity->sessions`)
- `list(array $params = []): array`
- `get(string $sessionId): array`
- `revoke(string $sessionId): array`
- `revokeAll(): array`

---

## Trade (`$essabu->trade`)

All Trade resources (customers, products, salesOrders, deliveries, receipts, suppliers, purchaseOrders, inventory, stock, warehouses, contacts, opportunities, activities, campaigns, contracts, documents) support standard CRUD:
- `list(array $params = []): array`
- `get(string $id): array`
- `create(array $data): array`
- `update(string $id, array $data): array`
- `delete(string $id): void`

---

## Payment (`$essabu->payment`)

All Payment resources (paymentIntents, paymentAccounts, transactions, refunds, subscriptions, subscriptionPlans, financialAccounts, loanProducts, loanApplications, loanRepayments, collaterals, kycProfiles, kycDocuments, reports) support standard CRUD.

---

## E-Invoice (`$essabu->eInvoice`)

- `invoices->normalize(array $data): array`
- `submissions->submit(string $invoiceId, ?array $metadata = null): array`
- `submissions->checkStatus(string $submissionId): array`
- `verification->verify(string $invoiceId): array`
- `compliance->generateReport(array $params): array`
- `statistics->get(array $params = []): array`

---

## Project (`$essabu->project`)

- `projects->list/get/create/update/delete`
- `milestones->list/get/create/update/delete` (scoped to projectId)
- `tasks->list/get/create/update/delete` (scoped to projectId)
- `taskComments->list/get/create/update/delete` (scoped to taskId)
- `resourceAllocations->list/get/create/update/delete` (scoped to projectId)
- `reports->list/get/create/delete` (scoped to projectId)

---

## Asset (`$essabu->asset`)

- `assets->list/get/create/update/delete` (standard CRUD)
- `vehicles->list/get/create/update/delete` (standard CRUD)
- `depreciations->list/get/create/update/delete` (scoped to assetId)
- `maintenanceSchedules->list/get/create/update/delete` (scoped to assetId)
- `maintenanceLogs->list/get/create/update/delete` (scoped to assetId)
- `fuelLogs->list/get/create/update/delete` (scoped to vehicleId)
- `tripLogs->list/get/create/update/delete` (scoped to vehicleId)
