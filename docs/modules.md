# Modules Reference

## HR (`$essabu->hr`)

| API | Access | Key Methods |
|-----|--------|-------------|
| Employees | `->employees` | `list`, `get`, `create`, `update`, `delete`, `terminate`, `reinstate`, `getDocuments` |
| Contracts | `->contracts` | CRUD + `renew`, `terminate` |
| Leaves | `->leaves` | CRUD + `approve`, `reject`, `getBalance` |
| Payroll | `->payrolls` | CRUD + `calculate`, `approve`, `generatePayslips` |
| Shifts | `->shifts` | CRUD |
| Departments | `->departments` | CRUD |
| Attendance | `->attendances` | CRUD + `clockIn`, `clockOut` |
| Recruitment | `->recruitments` | CRUD + `shortlist`, `hire` |
| Performance | `->performances` | CRUD + `submitReview` |
| Training | `->trainings` | CRUD + `enroll`, `complete` |
| Documents | `->documents` | CRUD + `upload`, `download` |
| Benefits | `->benefits` | CRUD + `assign` |
| Loans | `->loans` | CRUD + `approve`, `disburse`, `repay` |
| Timesheets | `->timesheets` | CRUD + `submit`, `approve` |
| Skills | `->skills` | CRUD |
| Onboarding | `->onboardings` | CRUD + `start`, `completeStep` |
| Expenses | `->expenses` | CRUD + `approve`, `reject`, `reimburse` |
| Disciplinary | `->disciplinary` | CRUD |
| Config | `->config` | CRUD |
| Reports | `->reports` | CRUD + `generate` |
| Webhooks | `->webhooks` | CRUD |
| History | `->history` | CRUD |

## Accounting (`$essabu->accounting`)

| API | Access | Key Methods |
|-----|--------|-------------|
| Accounts | `->accounts` | CRUD |
| Invoices | `->invoices` | CRUD + `finalize`, `send`, `void`, `markAsPaid`, `downloadPdf` |
| Payments | `->payments` | CRUD + `reconcile` |
| Quotes | `->quotes` | CRUD + `accept`, `reject`, `convertToInvoice` |
| Credit Notes | `->creditNotes` | CRUD + `apply` |
| Journals | `->journals` | CRUD + `post`, `reverse` |
| Wallets | `->wallets` | CRUD + `getBalance`, `getTransactions` |
| Tax Rates | `->taxRates` | CRUD |
| Currencies | `->currencies` | CRUD |
| Fiscal Years | `->fiscalYears` | CRUD + `close` |
| Insurance | `->insurances` | CRUD |
| Inventory | `->inventory` | CRUD + `adjust` |
| Coupons | `->coupons` | CRUD + `validate` |
| Reports | `->reports` | CRUD + `balanceSheet`, `profitAndLoss`, `trialBalance`, `cashFlow` |
| Config | `->config` | CRUD |
| Webhooks | `->webhooks` | CRUD |

## Identity (`$essabu->identity`)

| API | Access | Key Methods |
|-----|--------|-------------|
| Auth | `->auth` | `login`, `logout`, `refresh`, `forgotPassword`, `resetPassword`, `verifyEmail` |
| Users | `->users` | CRUD + `assignRole`, `removeRole`, `activate`, `deactivate` |
| Roles | `->roles` | CRUD + `assignPermissions` |
| Permissions | `->permissions` | CRUD |
| Tenants | `->tenants` | CRUD |
| Companies | `->companies` | CRUD |
| Branches | `->branches` | CRUD |
| Profile | `->profile` | CRUD + `me`, `updateMe`, `changePassword` |
| Sessions | `->sessions` | CRUD + `revoke`, `revokeAll` |
| API Keys | `->apiKeys` | CRUD + `rotate`, `revoke` |

## Trade (`$essabu->trade`)

| API | Access | Key Methods |
|-----|--------|-------------|
| Customers | `->customers` | CRUD |
| Contacts | `->contacts` | CRUD |
| Opportunities | `->opportunities` | CRUD + `won`, `lost` |
| Products | `->products` | CRUD |
| Sales Orders | `->salesOrders` | CRUD + `confirm`, `cancel`, `fulfill` |
| Suppliers | `->suppliers` | CRUD |
| Purchase Orders | `->purchaseOrders` | CRUD + `approve`, `receive` |
| Campaigns | `->campaigns` | CRUD + `launch`, `pause` |
| Contracts | `->contracts` | CRUD + `sign`, `renew` |
| Documents | `->documents` | CRUD |
| Warehouses | `->warehouses` | CRUD |
| Stocks | `->stocks` | CRUD + `transfer`, `adjust` |
| Deliveries | `->deliveries` | CRUD + `ship`, `deliver` |
| Receipts | `->receipts` | CRUD |
| Activities | `->activities` | CRUD |
| Reports | `->reports` | CRUD + `salesSummary`, `pipeline` |

## Payment (`$essabu->payment`)

| API | Access | Key Methods |
|-----|--------|-------------|
| Payment Intents | `->paymentIntents` | CRUD + `confirm`, `capture`, `cancel` |
| Transactions | `->transactions` | CRUD |
| Refunds | `->refunds` | CRUD |
| Subscriptions | `->subscriptions` | CRUD + `cancel`, `resume`, `changePlan` |
| Loan Products | `->loanProducts` | CRUD |
| Loan Applications | `->loanApplications` | CRUD + `approve`, `reject`, `disburse` |
| KYC | `->kyc` | CRUD + `verify`, `submitDocuments` |
| Collaterals | `->collaterals` | CRUD |
| Financial Accounts | `->financialAccounts` | CRUD + `getBalance`, `getStatement` |
| Reports | `->reports` | CRUD |

## E-Invoice (`$essabu->eInvoice`)

| API | Access | Key Methods |
|-----|--------|-------------|
| Invoices | `->invoices` | CRUD + `sign`, `downloadXml` |
| Submissions | `->submissions` | CRUD + `submit`, `getStatus` |
| Verifications | `->verifications` | CRUD + `verify` |
| Compliance | `->compliance` | CRUD + `check`, `getRules` |
| Statistics | `->statistics` | CRUD + `overview` |

## Project (`$essabu->project`)

| API | Access | Key Methods |
|-----|--------|-------------|
| Projects | `->projects` | CRUD + `archive`, `getTimeline` |
| Tasks | `->tasks` | CRUD + `assign`, `complete`, `logTime` |
| Milestones | `->milestones` | CRUD + `complete` |
| Resource Allocations | `->resourceAllocations` | CRUD |
| Task Comments | `->taskComments` | CRUD |
| Reports | `->reports` | CRUD + `burndown` |

## Asset (`$essabu->asset`)

| API | Access | Key Methods |
|-----|--------|-------------|
| Assets | `->assets` | CRUD + `assignTo`, `dispose` |
| Vehicles | `->vehicles` | CRUD |
| Maintenance Schedules | `->maintenanceSchedules` | CRUD |
| Maintenance Logs | `->maintenanceLogs` | CRUD + `complete` |
| Depreciation | `->depreciations` | CRUD + `calculate`, `getSchedule` |
| Fuel Logs | `->fuelLogs` | CRUD |
| Trip Logs | `->tripLogs` | CRUD |
