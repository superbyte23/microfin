# Multi-Tenant Data Isolation Plan

## Goal
Add cooperative-level multi-tenancy so one installation can serve multiple independent cooperatives with complete data isolation.

## Current State
Single-tenant — one database, one cooperative. Branches exist as internal units only. All users see all data. Registration creates a bare user with no organization context.

## Schema Foundation
`branch_id` already exists on 8 core tables (clients, loans, transactions, savings, cash_sessions, journal_entries, client_groups, users) and is derivable through parent relationships on 6 more. `loan_products`, `savings_products`, and `chart_of_accounts` are system-wide (no branch scope).

## Design Decisions
- **Cooperative creation**: Super-admin creates cooperatives (invite-only)
- **Reference data**: Per-cooperative (products and COA are scoped per coop)
- **Registration**: Requires invite code from super-admin, or super-admin creates accounts directly
- **Spatie teams**: Enable `teams` feature for per-cooperative roles/permissions

---

## Phase 1 — Foundation

### 1. Create `Cooperative` model + migration

| Column | Type | Notes |
|--------|------|-------|
| `id` | uuid | PK |
| `name` | string | |
| `code` | string | Unique slug |
| `address` | text | nullable |
| `phone` | string | nullable |
| `email` | string | nullable |
| `is_active` | boolean | default true |
| `created_at` / `updated_at` | timestamps | |

### 2. Add `is_super_admin` to `users`

Migration: `boolean is_super_admin default false`. Super-admin has no cooperative_id.

### 3. Add `cooperative_id` to tables

| Table | Constraint |
|-------|-----------|
| `branches` | FK, NOT NULL |
| `users` | FK, nullable (null = super-admin) |
| `loan_products` | FK, NOT NULL; unique with `code` |
| `savings_products` | FK, NOT NULL; unique with `code` |
| `chart_of_accounts` | FK, NOT NULL; unique with `code` |

### 4. Seed default cooperative

Seed "Main Cooperative" so existing data has a home.

### 5. Update seeders

- `BranchSeeder` — associates branches with default cooperative
- `LoanProductSeeder` / `SavingsProductSeeder` / chart of accounts — scoped to default cooperative

---

## Phase 2 — Scoping

### 6. Create `BelongsToCooperative` trait

```php
trait BelongsToCooperative
{
    protected static function bootBelongsToCooperative(): void
    {
        static::addGlobalScope('cooperative', function (Builder $builder) {
            if ($cooperativeId = auth()->user()?->cooperative_id) {
                $builder->where('cooperative_id', $cooperativeId);
            }
        });
    }
}
```

### 7. Apply trait to models

Branch, User, LoanProduct, SavingsProduct, ChartOfAccount.

Other models (Client, Loan, etc.) derive cooperative scope through `branch → cooperative` relationship — no `cooperative_id` needed on those tables.

### 8. Enable Spatie teams

Set `'teams' => true` in `config/permission.php`. `team_foreign_key` maps to `cooperative_id`. Each cooperative's roles/permissions are isolated.

---

## Phase 3 — UI & Registration

### 9. Super-admin panel

CRUD for cooperatives at `/admin/cooperatives` (super-admin only). Create, edit, activate/deactivate cooperatives.

### 10. Updated registration

- New staff registration requires a `cooperative_code` (invite code assigned by super-admin)
- Or super-admin creates user accounts directly from the admin panel
- New users auto-scoped to the correct cooperative

### 11. Super-admin dashboard

Super-admin sees a cooperative switcher and can view/monitor all cooperatives. Regular users only see their own coop's data.

---

## Phase 4 — Verification

### 12. Audit queries

Manual pass through every Livewire component, report, and export to ensure scoped queries work correctly.

### 13. Test isolation

- Create two test cooperatives with overlapping data
- Verify no data leakage between cooperatives

---

## What Stays Unchanged

| Aspect | Reason |
|--------|--------|
| `client_documents`, `loan_amortization_schedules`, `savings_transactions`, `share_capital_transactions`, `cash_transactions`, `journal_items` | No `cooperative_id` needed — derive scope through parent |
| `loans`, `clients`, `savings_accounts`, `share_capitals`, `cash_sessions`, `journal_entries` | Already have `branch_id` → cooperative through branch |
| All existing form/UI templates | Global scopes handle filtering transparently |
| Login form | Same UI, backend scopes via auth user |

---

## Migration Risk

- **Zero data loss** — all existing records assigned to default seeded cooperative
- `cooperative_id` added as nullable first, backfilled with default coop ID, then made NOT NULL
- Seeders updated to reference default cooperative
