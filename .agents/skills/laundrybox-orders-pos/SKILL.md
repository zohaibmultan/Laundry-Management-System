---
name: laundrybox-orders-pos
description: Safely modify the POS screen, order creation/editing, and customer package (subscription) usage logic in LaundryBox. Use for any change touching app/Livewire/Orders/PosScreen.php, order totals, or package-limited ordering.
---

# Orders and POS

Use this skill for any change that touches order creation, editing, printing, order-status,
or customer packages inside an order.

## Scope
- `app/Livewire/Orders/PosScreen.php` drives the whole POS workflow, including editing an
  existing order (`orders.pos.edit` route passes `$id` to the same component `mount()`).
  This is the largest and most sensitive component in the app — read broadly before editing.
- Related order screens: list, view, print, and status screens (each its own component).
- Payments, customers, services, service details, addons, packages, and order details are
  tightly coupled — a change to totals or line items usually needs to touch several of these.

## Data model for customer packages
- `Package` — `title, subtitle, items_per_week (int), duration (int, days), status (bool), price (double)`.
  `hasMany(PackageDetail)`, `hasMany(CustomerPackage)`.
- `PackageDetail` — pivot-like table: `package_id`, `service_detail_id`. Defines *which*
  service/service-type combinations are included in a package.
- `CustomerPackage` — `customer_id`, `package_id`, `assigned_by`. Represents one customer
  being assigned one package (created from the "Assign Package" screen).
- `Order.customer_package_id` (nullable FK → `customer_packages`, `nullOnDelete`) — set when
  an order was placed against an active package rather than paid normally.
- `Order belongsTo CustomerPackage` via `customerPackage()`.

## POS package-integration state (PosScreen.php)
```php
public $customer_packages, $selected_customer_package_id, $selected_customer_package;
public $package_service_detail_ids = [], $package_service_type_ids = [], $package_service_ids = [],
       $package_total_quantity = 0, $package_remaining_quantity = 0;
```

Key methods and what they do:
- `selectCustomer($id)` → after resolving the customer, loads their active packages:
  `CustomerPackage::with('package')->where('customer_id', $id)->whereHas('package', fn($q) => $q->where('status', 1))->get()`.
  If any active packages exist, the POS view shows a package dropdown below the customer field.
- `loadCustomerPackages()` — same query, used to refresh the list.
- `applySelectedCustomerPackage($customerPackageId)` — the core of the feature:
  1. Loads the `CustomerPackage` with `package.details.serviceDetail.serviceType`.
  2. Derives `package_service_detail_ids`, `package_service_type_ids`, `package_service_ids`
     from the package's `PackageDetail` rows.
  3. Sets `package_total_quantity = (int) $customerPackage->package->items_per_week`.
  4. Calls `refreshPackageRemainingQuantity()`.
- `pruneCartToPackageServices()` — when a package is selected, removes any cart line whose
  service type isn't in `package_service_type_ids`. Called right after a package is chosen so
  the cart can't hold out-of-package items.
- `refreshPackageRemainingQuantity()` — `package_remaining_quantity = max(package_total_quantity - usedQuantity, 0)`,
  where `usedQuantity` is summed from cart lines matching the package's allowed service types.
  Returns `0` immediately if no package is selected or `package_total_quantity <= 0`.
- `packageUsageSummary($customerPackage)` — returns a `"remaining/total"` string (e.g. `"3/7"`)
  used to label each option in the package dropdown.

## Behavior rules implemented in the POS UI
- Selecting a customer with active packages shows a package dropdown under the customer field;
  the dropdown option text includes the `remaining/total` summary from `packageUsageSummary()`.
- Once a package is selected, the item-type popup (clicking a service in the grid) only offers
  service types present in `package_service_type_ids` — the customer can't order something
  outside their package.
- Cart lines added under a package price at **0** (the package already covers the cost) —
  when placing an order with a package, cart line price = 0, not the service's normal price.
- The quantity `+`/`-` buttons on a package-priced cart line disable once the item's remaining
  package quantity hits 0.
- Order persistence: `'customer_package_id' => $this->selected_customer_package_id ?? null` is
  included in both the `save()` and `update()` payloads.
- Validation: if a customer has active packages but none is selected, `addError('customer_package_id', 'Select a package.')`
  blocks the save.

## Editing guidance
- Keep array indexes and recalculation paths consistent when changing line-item logic —
  cart lines are plain indexed arrays, not a Livewire entangled collection.
- Re-check tax behavior before adjusting price calculations; totals depend on discount, tax
  settings, quantity, selected services, addons, **and** whether a package is applied (package
  lines contribute 0 to `sub_total`).
- When editing order persistence, inspect the related models: `Order`, `OrderDetail`,
  `OrderAddonDetail`, and `Payment` — they're saved together, not independently.
- Use the existing permission model for create/edit flows instead of adding ad hoc checks.
- POS editing (`orders.pos.edit`) rebuilds component state from the saved order and its
  related details in `mount()`, including re-deriving package state via
  `applySelectedCustomerPackage($this->order->customer_package_id)` when the order has one.

## ⚠️ Known open item — weekly package quota is NOT yet enforced across orders
`package_remaining_quantity` is currently computed **only from the current cart**. A request
to cap a customer's package usage at `items_per_week` **per calendar week across all their
orders** (so splitting one big order into several smaller ones can't bypass the limit) was
started but left unfinished:
- The investigation had confirmed the cap is cart-only and identified that weekly usage needs
  to be anchored to *saved* `Order`/`OrderDetail` rows (filtered by `order_date` falling in the
  current week and `customer_package_id` matching), then subtracted from `package_total_quantity`
  before applying the existing cart-based remaining-quantity logic on top.
- Still undecided when this was paused: whether cancelled/unpaid orders should count toward
  usage, and what "current week" is anchored to (calendar week vs. rolling 7 days vs. package
  assignment date).
- If asked to "fix" or "finish" weekly package limits, treat this as completing existing work,
  not investigating from scratch — start by adding a `weeklyUsedQuantity($customerPackage)`
  helper that queries `Order::where('customer_package_id', ...)->whereBetween('order_date', [...])->withSum('details', 'quantity')`
  (or equivalent) and folding its result into `refreshPackageRemainingQuantity()`.
