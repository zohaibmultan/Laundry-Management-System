---
name: laundrybox-overview
description: Builds a fast mental model of the LaundryBox Laravel 11 + Livewire codebase — module boundaries, routing, and access control. Use before starting any new feature or bug fix so changes land in the right module.
---

# LaundryBox Overview

Use this skill when you need a fast mental model of the repository before coding, or when a
request doesn't obviously belong to one of the other LaundryBox skills yet.

## Core stack
- Laravel 11 application with Livewire 3 components doing double duty as the controller layer.
- Frontend assets built with Vite and Tailwind, layered on top of a Bootstrap-style admin
  template (`tw-` prefixed classes mixed with plain Bootstrap classes in the same markup).
- Composer autoloads `app/helper.php` for global helpers (e.g. `getFormattedCurrency()`).

## Main application areas
- Authentication and session flow live under `app/Livewire/Auth`.
- Installer and updater screens live under `app/Livewire/Installer` and sit outside the
  normal "installed app" middleware gate.
- Dashboard, POS, orders, customers, payments, services, packages, reports, expense, and
  settings are all Livewire-driven, one module folder per concern.
- Access control is split by middleware: `InstalledMiddleware` (app must be installed),
  `Store` (staff/store-level access to `/admin`), `Admin` (admin-only sub-areas).

## Routing map
- `routes/web.php` is the single source of truth and maps almost every page directly to a
  Livewire component — there's no separate controller/route-resource layer to check.
- Admin routes are grouped under `/admin`.
- Reports have dedicated print and download subroutes, gated behind `Admin` middleware.
- Installer routes are exposed *before* the installed-middleware gate.
- CRUD modules follow `GET /module` (list) + `GET /module/manage/{id?}` (create-or-edit)
  rather than separate create/edit routes.

## Working rules
- Prefer changing the owning Livewire component or model instead of adding route-level logic
  — there usually isn't a controller to put it in, and adding one breaks the pattern.
- Keep changes aligned with existing module boundaries; a new concern gets its own
  `app/Livewire/<Module>` folder rather than being bolted onto an existing one.
- Check related model relationships before editing views or component state — most modules
  are relationally coupled (e.g. Packages ↔ CustomerPackage ↔ Order, Service ↔ ServiceDetail ↔ PackageDetail).
- Validate against the app flow for install, login, and admin access before widening any
  change that touches middleware or route grouping.
- For the full list of proven UI/component/model conventions, load `laundrybox-livewire-patterns`.
- For anything touching the POS screen, orders, or packages-in-orders, load `laundrybox-orders-pos`.
- For scaffolding a brand-new CRUD module end-to-end, load `laundrybox-new-crud-module`.
