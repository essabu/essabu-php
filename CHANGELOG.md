# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-03-26

### Added

- Initial release of the Essabu PHP SDK
- Unified Stripe-like API: `new Essabu($apiKey, $tenantId)`
- Lazy module initialization via `__get` magic
- **HR Module**: Employees, Contracts, Leaves, Payroll, Shifts, Departments, Attendance, Recruitment, Performance, Training, Documents, Benefits, Loans, Timesheets, Skills, Onboarding, Expenses, Disciplinary, Config, Reports, Webhooks, History
- **Accounting Module**: Accounts, Invoices, Payments, Quotes, Credit Notes, Journals, Wallets, Tax Rates, Currencies, Fiscal Years, Insurance, Inventory, Coupons, Reports, Config, Webhooks
- **Identity Module**: Auth, Users, Roles, Permissions, Tenants, Companies, Branches, Profile, Sessions, API Keys
- **Trade Module**: Customers, Contacts, Opportunities, Products, Sales Orders, Suppliers, Purchase Orders, Campaigns, Contracts, Documents, Warehouses, Stocks, Deliveries, Receipts, Activities, Reports
- **Payment Module**: Payment Intents, Transactions, Refunds, Subscriptions, Loan Products, Loan Applications, KYC, Collaterals, Financial Accounts, Reports
- **E-Invoice Module**: Invoices, Submissions, Verifications, Compliance, Statistics
- **Project Module**: Projects, Tasks, Milestones, Resource Allocations, Task Comments, Reports
- **Asset Module**: Assets, Vehicles, Maintenance Schedules, Maintenance Logs, Depreciation, Fuel Logs, Trip Logs
- HTTP client with Guzzle, automatic retry on 429/5xx
- Exception hierarchy: Authentication (401), Authorization (403), NotFound (404), Validation (422), RateLimit (429), Server (5xx)
- Pagination support with `PageRequest` and `PageResponse`
- 8 example files covering all modules
- Full test suite with PHPUnit 11
