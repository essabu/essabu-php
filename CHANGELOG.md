# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-03-26

### Added
- Unified SDK merging all 8 Essabu PHP SDKs into a single package
- Stripe-like API: `$essabu->hr->employees->create([...])`
- HR module: 24 API classes (employees, departments, positions, contracts, attendance, leaves, shifts, payrolls, expenses, recruitment, performance, onboarding, documents, disciplinary, benefits, loans, timesheets, skills, reports, webhooks, config, history)
- Accounting module: 32 API classes organized in Core, Transactions, Finance, Commercial, and Inventory sub-modules
- Identity module: 10 API classes (auth, users, profiles, companies, tenants, roles, permissions, branches, apiKeys, sessions)
- Trade module: 16 API classes (customers, products, salesOrders, deliveries, receipts, suppliers, purchaseOrders, inventory, stock, warehouses, contacts, opportunities, activities, campaigns, contracts, documents)
- Payment module: 14 API classes (paymentIntents, paymentAccounts, transactions, refunds, subscriptions, subscriptionPlans, financialAccounts, loanProducts, loanApplications, loanRepayments, collaterals, kycProfiles, kycDocuments, reports)
- E-Invoice module: 5 API classes (invoices, submissions, verification, compliance, statistics)
- Project module: 6 API classes (projects, milestones, tasks, taskComments, resourceAllocations, reports)
- Asset module: 7 API classes (assets, depreciations, maintenanceSchedules, maintenanceLogs, vehicles, fuelLogs, tripLogs)
- Shared HTTP client with retry logic, rate limit handling, and error mapping
- Unified exception hierarchy (EssabuException, ValidationException, NotFoundException, etc.)
- Pagination support via PageRequest/PageResponse
- Webhook signature verification
- PHP 8.2+ with strict types everywhere
- 5 GitHub Actions workflows (CI, release, code style, security, docs)
