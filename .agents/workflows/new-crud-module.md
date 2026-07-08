---
name: new-crud-module
description: Scaffold a brand-new settings-style CRUD module in LaundryBox end-to-end (migration, model, Livewire list + manage pages, route group, sidebar link).
---

# /new-crud-module

Use this workflow when the user asks to add a new manageable entity to LaundryBox
(packages, categories, types, etc.) with a list of fields.

1. Confirm the entity name and its fields (name + type) from the request. If any field's
   type or nullability is ambiguous, make the same defaults the Packages module used
   (`string` nullable for secondary text fields, `integer` for counts, `boolean` default
   `true` for status, `double(15,2)` default `0` for money) rather than asking — state the
   assumption in the summary at the end instead of blocking on it.
2. Load the `laundrybox-new-crud-module` skill and follow its 8-step recipe exactly, using
   the templates in its `references/` folder as the starting point for each file.
3. Load `laundrybox-livewire-patterns` for the exact Blade markup (buttons, tables, forms,
   switches) so the new pages are visually and structurally identical to existing modules —
   do not invent new markup or class names.
4. Load `laundrybox-project-context` (it's always active, but re-check it) for the
   permission-check and translation conventions before writing the components.
5. After scaffolding, run the new migration and load the new list page to confirm it renders
   without errors.
6. Summarize what was created (migration, model, routes, components, views, sidebar entry)
   and call out any assumption made in step 1.
