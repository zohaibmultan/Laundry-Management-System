---
name: laundrybox-admin-reports-settings
description: Handle admin-only reporting, settings, translations, roles, and installation/update flows in LaundryBox. Use for changes under app/Livewire/Reports, app/Livewire/Settings, app/Livewire/Roles, or app/Livewire/Installer.
---

# Admin, Reports, and Settings

Use this skill for admin-side maintenance work outside the POS flow.

## Scope
- Reporting covers daily, expense, ledger, order, sales, and tax views.
- Print and download report routes have their own Livewire component trees
  (`Reports\PrintReport\*`, `Reports\DownloadReport\*`), separate from the on-screen report components.
- Settings include master settings, mail, financial year, SMS, theme, file tools,
  translations, roles, staff, and packages (Packages settings module lives at
  `settings/packages/*` and follows the standard CRUD module pattern — see
  `laundrybox-new-crud-module`).
- Installer pages and update screens are separate from the authenticated admin area and are
  reachable before the app is marked installed.

## Access control
- `Admin` middleware protects admin-only access (e.g. print/download report routes).
- `Store` middleware allows store and admin users into operational screens under `/admin`.
- `InstalledMiddleware` redirects to install or update flows when the app is not ready —
  don't assume a request always reaches an authenticated route.

## Editing guidance
- Keep report filters and output routes in sync when changing reporting behavior — the
  on-screen, print, and download variants of a report are three separate components that
  must agree on filter parameters and date handling.
- Check middleware and route grouping before changing admin page access; a route added
  outside the expected group silently loses its access control.
- Review translation and session language behavior (`session('selected_language')`, the
  `Translation` model's `default` flag) before updating labels or localized views — see
  `laundrybox-livewire-patterns` for the exact `$lang->data[...]` convention.
- Treat install and update routes carefully because they sit outside the normal authenticated
  app flow and can be reached with no session at all.
