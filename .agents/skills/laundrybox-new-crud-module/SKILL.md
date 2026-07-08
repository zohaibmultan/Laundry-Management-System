---
name: laundrybox-new-crud-module
description: Step-by-step recipe for scaffolding a brand-new settings-style CRUD module in LaundryBox (model, migration, Livewire list + manage pages, route group, sidebar link). Use when asked to "create a new module" or add a new manageable entity such as packages, categories, or types.
---

# Scaffolding a New CRUD Module

This is the exact recipe used to build the Packages module (title, subtitle, items per week,
duration, status, price) and is proven to fit this codebase. Follow it in order. Ready-to-adapt
templates are in `references/`.

## When to use this skill
Trigger this for requests shaped like *"create a new module `X`. It can have field A (type),
field B (type)... create database model, migration, and livewire pages (similar like other
pages, use same input fields, button, tables, notifications etc)."*

## Steps

1. **Migration** — `database/migrations/<timestamp>_create_<table>_table.php`.
   One column per requested field, typed appropriately, `status` as `boolean()->default(true)`,
   money fields as `double($col, 15, 2)->default(0)`, always end with `$table->timestamps()`.
   See `references/migration.php.tpl`.

2. **Model** — `app/Models/<Entity>.php`. `use HasFactory;`, explicit `$fillable` matching the
   migration columns, explicit `$casts` for booleans/integers/floats, and relationship methods
   for anything the field list implies (e.g. a `status` + related detail rows imply a `hasMany`
   detail relation). See `references/model.php.tpl`.

3. **Route group** — add to `routes/web.php` inside the appropriate existing group (settings
   modules go under the `settings/` prefix group). Standard shape:
   ```php
   Route::group(['prefix' => '<module>/'], function () {
       Route::get('/', \App\Livewire\<Module>\<Entity>List::class)->name('<module>.list');
       Route::get('/manage/{id?}', \App\Livewire\<Module>\<Entity>Manage::class)->name('<module>.manage');
   });
   ```
   Add any extra screens the request implies (assign/detail screens etc.) as siblings in the
   same group, following the same `<module>.<action>` naming.

4. **List component + view** — `app/Livewire/<Module>/<Entity>List.php` and
   `resources/views/livewire/<module>/<entity>-list.blade.php`. Follow the list-component
   pattern and table markup exactly as documented in `laundrybox-livewire-patterns`
   (search box, status filter if the entity has a status column, toolbar action buttons
   wrapped in `@can`, table with index/edit/delete columns, `<x-empty-item />` for empty state).
   See `references/list-component.php.tpl` and `references/list-view.blade.php.tpl`.

5. **Manage component + view** — `app/Livewire/<Module>/<Entity>Manage.php` and
   `resources/views/livewire/<module>/<entity>-manage.blade.php`. One component for both
   create and edit, `mount($id = null)`, matching `save()`/`update()` validation, status
   toggle switch with `@checked()`, form footer with conditional Save/Update button.
   See `references/manage-component.php.tpl` and `references/manage-view.blade.php.tpl`.

6. **Sidebar link** — add an entry to `resources/views/livewire/components/sidebar.blade.php`.
   If the module lives under Settings, add it as a submenu `<li>` inside the existing settings
   dropdown, wrapped in `@can('<permission>')`, alphabetically/logically placed next to related
   entries:
   ```blade
   @can('setting_view')
   <li>
       <a href="{{ route('<module>.list') }}"><i
               class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
           {{ $lang->data['<module>'] ?? '<Module Label>' }}</a>
   </li>
   @endcan
   ```

7. **Permission** — reuse an existing permission string that plausibly covers the new module's
   area unless the request explicitly asks for a new granular permission (the Packages module
   reused `setting_view` end-to-end rather than defining new permissions).

8. **Run the migration** and sanity-check the new list/manage pages render without errors
   before declaring the module done.

## Extending an existing module (not a brand-new one)
If the request builds *on top of* an existing module (e.g. "also show a checkbox table of
related X on the manage page") rather than creating a new one:
- Add the new columns/relations via a fresh migration and model relationship — don't touch the
  original migration.
- Follow the checkbox-array multi-select pattern from `laundrybox-livewire-patterns` if the
  addition is a multi-select of related rows.
- Keep the existing `save()`/`update()` structure; add the new persistence logic (e.g. sync a
  pivot-like `*_details` table) inside both methods, in parallel, not as a shared extracted method.

## Reference templates
- `references/migration.php.tpl` — create-table migration skeleton.
- `references/model.php.tpl` — model skeleton with fillable/casts/relations.
- `references/list-component.php.tpl` — list component skeleton.
- `references/list-view.blade.php.tpl` — list view skeleton (toolbar + table).
- `references/manage-component.php.tpl` — add/edit component skeleton.
- `references/manage-view.blade.php.tpl` — add/edit view skeleton (form + switch + footer).

Read a template with its `--help`-equivalent (i.e. just open it) only when actually scaffolding
that file — don't load all six templates into context for an unrelated question.
