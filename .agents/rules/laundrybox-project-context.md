---
name: laundrybox-project-context
description: Always-active project context for the LaundryBox Laravel + Livewire app — stack, module map, and non-negotiable coding conventions.
---

# LaundryBox — Project Context

LaundryBox is a laundry/laundromat management system built on **Laravel 11 + Livewire 3**.
This rule is always active. Read it before touching any file in this workspace.

## Stack
- Laravel 11, Livewire 3 full-page components (there is effectively no separate controller
  layer — `routes/web.php` maps almost every URL directly to a Livewire component class).
- Vite + Tailwind for the build pipeline, but the UI itself is a Bootstrap-style admin
  template (WowDash-like) with Tailwind utility classes mixed in under a `tw-` prefix.
- Icons are rendered with the `<iconify-icon icon="...">` web component, not an icon font or SVG import.
- `app/helper.php` is autoloaded by Composer and provides global helpers such as
  `getFormattedCurrency()`. Always use it to render money values instead of raw interpolation.

## Module map
- `app/Livewire/Auth` — login, logout, forgot/reset password.
- `app/Livewire/Installer` — first-run install and update wizards (sit **outside** the
  `InstalledMiddleware` gate — be careful when touching these).
- `app/Livewire/Orders` — POS screen, order list/view/print, order status screen.
- `app/Livewire/Customers`, `Payments`, `Service`, `Packages`, `Expense`, `Reports`,
  `Settings`, `Roles` — each is a self-contained Livewire module under `admin/`.
- `resources/views/livewire/<module>/*.blade.php` mirrors `app/Livewire/<Module>` 1:1
  (kebab-case view file per PascalCase component).

## Routing (`routes/web.php`)
- Un-authenticated: `/license`, `/install`, `/update`, `/reset-password/{token}`.
- Everything else sits inside `InstalledMiddleware`, then `/admin` inside `Store` middleware
  (staff-level access). `Admin` middleware gates admin-only sub-routes (e.g. print/download reports).
- Convention for a CRUD module: `GET /manage/{id?}` handles **both** create and edit with one
  Livewire component; there is no separate "create" route.
- Route names follow `<module>.<action>` (e.g. `packages.manage`, `packages.list`,
  `orders.pos`, `orders.pos.edit`).

## Access control
- `Gate::allows('<permission>')` (abort 404 on failure) gated in `mount()` — this is the
  **page-level** check.
- `@can('<permission>')` / `@canany([...])` in Blade — this is the **element-level** check
  (buttons, table actions, sidebar entries).
- Never invent a new permission-check style; reuse an existing permission string if one
  plausibly already covers the area (e.g. settings-style CRUD modules reuse `setting_view`).

## Non-negotiable conventions (verified in the codebase — don't deviate)
1. **i18n:** every visible string goes through
   `{{ $lang->data['snake_case_key'] ?? 'Human Fallback' }}`. `$lang` is loaded once in
   `mount()` from `session('selected_language')` (fallback: the `Translation` marked
   `default => 1`). Never hardcode a bare string in a Blade file.
2. **Feedback/notifications:** actions call
   `$this->dispatch('alert', ['type' => 'success'|'error', 'message' => '...'])`. This is
   the *only* feedback mechanism used — no session flash, no SweetAlert calls, no `session()->flash()`.
3. **List components:** public `$items`, `$search_query`, `$xxx_filter = ''` properties;
   a `load<Items>()` method builds the query with conditional `where()`s; `updated($name, $value)`
   re-runs the loader when a search/filter property changes; `delete($id)` is wrapped in
   `try/catch` and dispatches a success or error alert.
4. **Manage (add/edit) components:** one component handles both flows via `mount($id = null)`;
   `save()` and `update()` are separate methods with an **identical** `$this->validate([...])`
   rule set; both redirect to the module's `.list` route and dispatch a success alert.
5. **Models:** `use HasFactory;` explicit `$fillable`; explicit `$casts` for booleans/numerics;
   relationship method names describe the *related concept*, not the raw column
   (`customerPackages()`, `package()`, `serviceDetail()`), singular for `belongsTo`, plural
   for `hasMany`.
6. **Migrations:** one file per table or per schema change (never edit an already-shipped
   migration — add a new `add_..._to_..._table.php` migration instead). Foreign keys via
   `$table->foreignId('x_id')->constrained('table')->cascadeOnDelete()` (required relation)
   or `->nullOnDelete()` (optional relation). Money columns: `$table->double('price', 15, 2)->default(0)`.
7. **Boolean toggle switches are a known footgun:** a status checkbox must pair
   `wire:model="status"` with an explicit `@checked($status)`, or it will fail to reflect
   the saved value when editing an existing record. This was an actual bug fixed in this
   codebase — always include both.

For UI markup specifics (buttons, tables, forms, checkboxes-as-multiselect) and step-by-step
module scaffolding, see the `laundrybox-livewire-patterns` and `laundrybox-new-crud-module` skills.
For POS/order/package interaction logic, see the `laundrybox-orders-pos` skill.

## Known open item (left mid-implementation by a prior session)
The POS screen currently caps package usage against the **current cart only**
(`package_remaining_quantity` computed from `items_per_week` minus quantity already added to
the cart). A request to cap usage against **all orders placed within the current week**
(so a customer can't exceed their weekly quota across multiple separate orders) was started
but not finished — the investigation had reached the point of deciding how to compute
weekly-used quantity from saved `Order`/`OrderDetail` rows before the session ended. If asked
to "fix the weekly package quota" or similar, treat this as unfinished work, not a regression.
