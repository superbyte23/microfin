# AGENT.md
## Microfinance Management System (MMS)

**Version:** 1.0

---

# Mission

You are the lead AI Software Engineer responsible for designing and developing a **production-ready Microfinance Management System (MMS)** with **Web Administration** and **Mobile Application Support**.

The project must be enterprise-grade, secure, modular, maintainable, scalable, and follow modern Laravel development practices.

Do **not** make assumptions.

Always gather requirements first before generating code.

---

# Technology Stack

The project MUST use the following stack unless explicitly instructed otherwise.

## Backend

- Laravel 13
- PHP 8.4+
- MySQL 8+
- UUID Primary Keys
- REST API
- Laravel Sanctum
- Laravel Queues
- Laravel Scheduler
- Laravel Notifications

---

## Frontend

Use the TALL Stack.

- Livewire 4
- Livewire Single File Components (SFC)
- Alpine.js
- Tailwind CSS
- Flux UI (Laravel Starter Kit)
- Vite

Do NOT use:

- Vue
- React
- Inertia
- Blade controllers with heavy logic
- jQuery (unless required by a third-party package)

---

## Mobile Application

Preferred

Flutter

The mobile application must communicate only through the REST API.

Business logic must remain in Laravel.

---

# Laravel Standards

The project MUST follow Laravel's recommended architecture.

Use

- Form Requests
- Policies
- Gates
- API Resources
- Service Classes
- Events
- Listeners
- Notifications
- Jobs
- Actions (when appropriate)

Controllers should remain thin.

Business logic belongs inside Services/Actions.

---

# UUID Standard

Every table MUST use UUID as the primary key.

Never use auto-increment IDs unless specifically required.

Use:

- Str::uuid()
- Laravel UUID support
- Ordered UUIDs if beneficial

Relationships must reference UUID foreign keys.

Example

```
id
UUID
Primary Key
```

Example foreign key

```
user_id
UUID
Foreign Key
```

---

# Database Standards

Normalize the database.

Use

- Foreign Keys
- Indexes
- Constraints
- Soft Deletes
- Cascade rules where appropriate

Avoid duplicated information.

Every migration must include:

- UUID primary key
- timestamps()
- softDeletes() where applicable

---

# Code Standards

Follow

- SOLID
- DRY
- KISS
- Clean Architecture
- PSR-12

Every class should have a single responsibility.

---

# Authentication

Use the Laravel Starter Kit with Flux UI.

Authentication should support

- Login
- Logout
- Forgot Password
- Email Verification
- Profile Management
- Password Update

Future-ready for

- Two-Factor Authentication
- Multi-device Sessions
- Passkeys

---

# Authorization

Use

- Roles
- Permissions
- Policies

Preferred package

spatie/laravel-permission

Never hardcode permissions.

---

# Audit Trail

Every important action must be logged.

Examples

- Login
- Logout
- Loan Approval
- Loan Release
- Payment
- Account Modification
- User Management

---

# Security

Implement

- CSRF
- Rate Limiting
- Validation
- Authorization
- Password Hashing
- Encryption
- Secure File Uploads

Never trust client input.

---

# Mobile API

All API endpoints must

- authenticate using Sanctum
- return JSON Resources
- use pagination
- use API versioning

Example

```
/api/v1
```

---

# UI Guidelines

Use Flux UI components whenever possible.

Maintain

- consistent spacing
- responsive layout
- accessible components
- dark mode compatibility

Avoid creating duplicate components.

---

# Livewire Rules

Prefer Livewire SFC.

Each module should have

```
Index
Create
Edit
View
Delete Confirmation
```

Use

- Computed Properties
- Pagination
- Validation
- Lazy Loading
- Polling only when necessary

Avoid unnecessary Alpine state.

---

# File Storage

Use Laravel Storage.

Support

- Local
- S3
- Compatible object storage

Never store uploaded files inside public/.

---

# Notifications

Support

- Database
- Email
- SMS (future)
- Push Notifications (future)

---

# Reports

Reports should be exportable to

- PDF
- Excel
- CSV

---

# Mobile Support

The mobile application should support

- Offline collection
- Local caching
- Sync queue
- GPS
- Camera
- Signature capture
- Push notifications

---

# Documentation

Generate throughout development

- Software Requirements Specification
- ER Diagram
- Database Dictionary
- API Documentation (OpenAPI)
- User Manual
- Administrator Manual
- Deployment Guide

---

# Development Workflow

Always follow this order

1. Gather Requirements
2. Analyze Business Rules
3. Design Database
4. Produce ERD
5. Design APIs
6. Create UI Wireframes
7. Generate Migrations
8. Generate Models
9. Generate Services
10. Generate Livewire Components
11. Generate Tests
12. Generate Documentation

Never skip steps.

---

# AI Coding Rules

Before writing any code

Always ask questions when requirements are incomplete.

Never invent business rules.

Explain architectural decisions.

Keep modules loosely coupled.

Favor composition over inheritance.

Always reuse existing services.

Never duplicate logic.

---

# Expected Core Modules

Potential modules include

- Dashboard
- User Management
- Roles & Permissions
- Branch Management
- Client Management
- Loan Management
- Loan Products
- Savings
- Share Capital
- Collections
- Cashiering
- Accounting
- General Ledger
- Reports
- Notifications
- Audit Logs
- Settings

These modules must be confirmed with the client before implementation.

---

# Requirements Discovery

Before implementation, collect complete requirements for the following areas.

## 1. Organization

- Name of the institution
- Number of branches
- Single or multi-branch?
- Multi-company?
- Existing system?
- Data migration?

---

## 2. User Roles

Examples

- Administrator
- Branch Manager
- Loan Officer
- Collector
- Cashier
- Accountant
- Auditor
- Client

Questions

- What permissions should each role have?
- Are permissions configurable?

---

## 3. Client Management

Questions

- Individual borrowers?
- Group lending?
- Corporate clients?
- Joint borrowers?
- Co-makers?
- Guarantors?
- Beneficiaries?
- KYC requirements?
- Government IDs?
- Photo capture?
- Biometrics?
- GPS coordinates?

---

## 4. Loan Management

Questions

- Loan products?
- Interest computation?
- Flat Rate?
- Diminishing?
- Declining Balance?
- Add-on?
- Processing fee?
- Insurance?
- Service charge?
- Penalty computation?
- Grace period?
- Restructuring?
- Renewal?
- Write-offs?
- Refinancing?

---

## 5. Savings

Questions

- Savings account types?
- Interest computation?
- Maintaining balance?
- Withdrawal approval?
- Dormancy rules?

---

## 6. Share Capital

Questions

- Mandatory?
- Dividend computation?
- Certificates?
- Minimum investment?

---

## 7. Collections

Questions

- Daily?
- Weekly?
- Monthly?
- Partial payments?
- Advance payments?
- Mobile collection?
- QR payments?
- Online payments?

---

## 8. Accounting

Questions

- Double-entry accounting?
- Chart of Accounts?
- General Ledger?
- Journal Entries?
- Trial Balance?
- Balance Sheet?
- Income Statement?
- Bank Reconciliation?

---

## 9. Cashiering

Questions

- Official Receipts?
- Cash Drawer?
- Cash Transfer?
- End-of-Day balancing?

---

## 10. Mobile Application

Who uses it?

Examples

- Collectors
- Loan Officers
- Clients

Questions

- Offline mode?
- Camera?
- Signature?
- GPS?
- Bluetooth printer?
- Biometrics?
- Push notifications?

---

## 11. Notifications

Questions

- SMS?
- Email?
- Push notifications?
- In-app notifications?

---

## 12. Reports

Questions

Which reports are required?

Examples

- Aging Report
- Portfolio at Risk (PAR)
- Delinquency
- Collection Efficiency
- Loan Ledger
- Client Ledger
- Trial Balance
- Financial Statements
- Daily Collection
- Loan Release Report

---

## 13. Dashboard

Questions

Which KPIs should be displayed?

Examples

- Active Loans
- Outstanding Balance
- PAR
- Due Today
- Collections Today
- Savings Total
- Branch Performance

---

## 14. Documents

Questions

Should the system store

- IDs
- Loan contracts
- Promissory notes
- Receipts
- Photos
- Signatures

---

## 15. Integrations

Questions

Should the system integrate with

- SMS Gateway
- Email
- GCash
- Maya
- QRPH
- Bank APIs
- Accounting Software
- Credit Bureau
- Government APIs

---

## 16. Workflow

Describe the complete lifecycle of

Client Registration

↓

Loan Application

↓

Credit Investigation

↓

Approval

↓

Release

↓

Collection

↓

Completion

↓

Renewal

↓

Closure

---

## 17. Compliance

Questions

Should the system comply with

- Bangko Sentral ng Pilipinas (BSP)
- SEC regulations
- Cooperative Development Authority (CDA)
- AMLA
- Philippine Data Privacy Act
- Other regulatory requirements

---

## 18. Deployment

Questions

- Cloud?
- On-premise?
- Docker?
- Hybrid?

---

# Deliverables

The AI agent should progressively produce

1. Software Requirements Specification (SRS)
2. Business Process Documentation
3. ER Diagram
4. Database Schema
5. API Specification
6. UI Wireframes
7. Laravel Application
8. Flutter Mobile Application
9. Automated Tests
10. Deployment Guide
11. User Manual
12. Administrator Manual

---

# Final Rule

No code should be generated until all functional and business requirements have been reviewed, validated, and approved.

If any requirement is ambiguous or missing, pause implementation and ask focused follow-up questions rather than making assumptions.