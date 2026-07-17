---
name: laundrybox-livewire-patterns
description: Concrete, verified conventions for writing or editing Livewire components and Blade views in LaundryBox — component structure, translations, permissions, notifications, tables, forms, and toggle switches. Use for any change inside app/Livewire or resources/views/livewire.
---

# Livewire Patterns

Use this skill whenever you're adding or editing a Livewire component or its Blade view in
this project. Everything below was observed directly in shipped code (the Packages module),
not guessed — follow it exactly rather than introducing a new style.

## Component skeleton
```php
class ExampleList extends Component
{
    #[Title('Example')]
    public $items, $search_query, $status_filter = '', $lang;

    public function mount()
    {
        if (!\Illuminate\Support\Facades\Gate::allows('setting_view')) {
            abort(404);
        }

        $this->loadItems();

        if (session()->has('selected_language')) {
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $this->lang = Translation::where('default', 1)->first();
        }
    }

    public function render()
    {
        return view('livewire.example.example-list');
    }
}
```
- `mount()` always: (1) gate the page, (2) load initial data, (3) load `$lang`. Don't reorder
  or drop any of the three.
- `render()` returns a single view; there's no layout logic beyond that.

## List component pattern
- `load<Items>()` builds the Eloquent query, applying `where()`/`orWhere()` only when the
  matching filter property is non-empty (`!== '' && !== null`).
- `updated($name, $value)` checks `$name` against the relevant search/filter property names
  and re-calls `load<Items>()` — this is how "live" filtering works, there's no separate
  `wire:submit`.
- `delete($id)` is wrapped in `try/catch`, calls the load method again on success, and
  **always** ends with a `dispatch('alert', ...)` call (success or error).

## Manage (add/edit) component pattern
- One component serves both create and edit. `mount($id = null)` hydrates every public
  property from the model when `$id` is given, otherwise properties keep their declared defaults.
- `save()` and `update()` are two separate methods with the *same* validation rule array
  (don't extract a shared method unless asked — this duplication is the existing pattern).
- Both end with `dispatch('alert', ['type' => 'success', 'message' => '...'])` then
  `return redirect()->route('<module>.list')`.

## Translations
- Every user-facing string: `{{ $lang->data['snake_case_key'] ?? 'Fallback Text' }}`.
- Never introduce a string without both a key and a readable fallback — the fallback is what
  renders until someone adds the key to the Translations settings screen.

## Permissions
- Page-level: `Gate::allows('permission_string')` in `mount()`, `abort(404)` on failure.
- Element-level: `@can('permission_string')` / `@canany([...])` wrapping the action/button in
  Blade. Reuse an existing permission string for a new module if one plausibly fits (this
  codebase reused `setting_view` for the entire Packages module rather than inventing new
  granular permissions).

## Notifications
- The only feedback channel is `$this->dispatch('alert', ['type' => 'success'|'error', 'message' => 'Human readable sentence.'])`.
- Do not use `session()->flash()`, raw `$this->addError()` for success cases, or a bespoke
  toast call.

## Blade markup building blocks

**Page wrapper**
```blade
<div class="dashboard-main-body">
    <div class="card h-100 p-0 radius-12">
        ...
    </div>
</div>
```

**Toolbar (search + filter + actions)**
```blade
<div class="tw-py-1.5 tw-px-3 bg-base d-flex align-items-center flex-wrap gap-3 justify-content-between">
    <form class="navbar-search d-flex gap-2 align-items-center">
        <input type="text" class="bg-base h-40-px w-auto" wire:model.live="search_query"
               placeholder="{{ $lang->data['search_here'] ?? 'Search Here' }}">
        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
    </form>
    <div class="d-flex gap-2 flex-wrap">
        @can('setting_view')
        <a href="{{ route('example.manage') }}"
           class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
            <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
            {{ $lang->data['add_new'] ?? 'Add New' }}
        </a>
        @endcan
    </div>
</div>
```

**Table + row actions**
```blade
<div class="table-responsive scroll-sm">
    <table class="table bordered-table sm-table mb-0">
        <thead><tr><th>#</th><!-- ... --><th class="text-center">Action</th></tr></thead>
        <tbody>
            @if(count($items) > 0)
                @foreach ($items as $item)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <!-- ... -->
                    <td class="text-center">
                        <div class="d-flex align-items-center gap-10 justify-content-center">
                            @can('setting_view')
                            <a href="{{ route('example.manage', $item->id) }}"
                               class="tw-bg-blue-100 tw-text-blue-600 hover:tw-bg-blue-300 fw-medium tw-size-8 d-flex justify-content-center align-items-center rounded-circle">
                                <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                            </a>
                            <button type="button" wire:click="delete({{ $item->id }})"
                                    class="remove-item-button bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium tw-size-8 d-flex justify-content-center align-items-center rounded-circle">
                                <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                            </button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    @if(count($items) == 0)
        <x-empty-item />
    @endif
</div>
```
- Status pills: `text-success-600 bg-success-100 ...` (active) vs `text-danger-600 tw-bg-red-100 ...` (inactive).
- Money values always go through `getFormattedCurrency($value)` — never raw interpolation.

**Form field**
```blade
<div class="col-md-6">
    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
        {{ $lang->data['title'] ?? 'Title' }} <span class="text-danger">*</span>
    </label>
    <input type="text" class="form-control radius-8" wire:model="title"
           placeholder="{{ $lang->data['enter_title'] ?? 'Enter Title' }}">
    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
</div>
```

**Boolean toggle switch — always pair `wire:model` with `@checked()`**
```blade
<div class="form-switch switch-primary d-flex align-items-center gap-3">
    <input class="form-check-input" type="checkbox" role="switch" wire:model="status" @checked($status)>
    <label class="form-check-label line-height-1 fw-medium text-secondary-light">
        {{ $lang->data['status'] ?? 'Status' }}
    </label>
</div>
```
Omitting `@checked($status)` was a real bug in this codebase: the switch didn't reflect the
saved value on the edit screen even though `wire:model="status"` was present.

**Checkbox-array multi-select (e.g. selecting several related rows)**
```blade
<input class="form-check-input" type="checkbox" role="switch" value="{{ $row->id }}"
       wire:model.live="selected_ids" @checked(in_array((string) $row->id, $selected_ids, true))>
```
Note the `(string)` cast on the id when checking `in_array` — the array is populated with
string values by Livewire's wire:model on checkboxes.

**Form footer**
```blade
<div class="col-12 d-flex align-items-center justify-content-end gap-3 mt-24">
    <a href="{{ route('example.list') }}" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
        {{ $lang->data['cancel'] ?? 'Cancel' }}
    </a>
    @if($item_id)
        <button type="button" class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8" wire:click.prevent="update">
            {{ $lang->data['update'] ?? 'Update' }}
        </button>
    @else
        <button type="button" class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8" wire:click.prevent="save">
            {{ $lang->data['save'] ?? 'Save' }}
        </button>
    @endif
</div>
```

## Development cues
- Preserve existing public property names when touching a component — views bind to them by name.
- Check for session-driven state (selected language) before changing UI logic.
- Use Eloquent queries consistent with the surrounding component instead of introducing new
  abstractions (repositories, service classes, form requests) unless explicitly asked.
- If a component already uses `Gate::allows(...)`, keep permission checks close to the entry point.

## Good targets for follow-up work
- Search and filter interactions.
- Form recalculation logic.
- Component-to-view binding cleanup.
- Validation and permission checks.

## QZ Tray Printing Integration Pattern

When integrating QZ Tray for silent/secure thermal label or receipt printing:

1. **Certificate & Signature Routes**:
   Define light, CSRF-free `GET` routes for fetching the digital certificate and signing requests to prevent handshake blockages:
   ```php
   Route::get('/qz/certificate', function () {
       return response(file_get_contents(storage_path('app/qz/digital-certificate.txt')))
           ->header('Content-Type', 'text/plain');
   })->name('qz.certificate');

   Route::get('/qz/sign', function (\Illuminate\Http\Request $request) {
       $req = $request->input('request');
       $privateKey = file_get_contents(storage_path('app/qz/private-key.pem'));
       $key = openssl_get_privatekey($privateKey);
       openssl_sign($req, $signature, $key, OPENSSL_ALGO_SHA512);
       return response(base64_encode($signature), 200)
           ->header('Content-Type', 'text/plain');
   })->name('qz.sign');
   ```

2. **Frontend Security Setup**:
   Configure QZ Tray to fetch the certificate and signatures asynchronously from these endpoints:
   ```javascript
   qz.security.setCertificatePromise((resolve, reject) =>
       fetch('/qz/certificate').then(r => r.text()).then(resolve, reject)
   );

   qz.security.setSignatureAlgorithm("SHA512");
   qz.security.setSignaturePromise(toSign => (resolve, reject) =>
       fetch('/qz/sign?request=' + encodeURIComponent(toSign))
           .then(r => {
               if (!r.ok) {
                   return r.text().then(text => {
                       throw new Error('Sign endpoint returned ' + r.status + ': ' + text);
                   });
               }
               return r.text();
           })
           .then(resolve, reject)
   );
   ```
