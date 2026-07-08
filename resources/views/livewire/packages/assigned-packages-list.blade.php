<div class="dashboard-main-body">
    <div class="card h-100 p-0 radius-12">
        <div class="tw-py-1.5 tw-px-3 bg-base d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <form class="navbar-search d-flex gap-2 align-items-center">
                    <input type="text" class="bg-base h-40-px w-auto" placeholder="{{ $lang->data['search_here'] ?? 'Search Here' }}" wire:model.live="search_query">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
                <select class="form-select h-40-px w-auto" wire:model.live="customer_filter">
                    <option value="">{{ $lang->data['all_customers'] ?? 'All Customers' }}</option>
                    @foreach(\App\Models\Customer::where('is_active', 1)->latest()->get() as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
                <select class="form-select h-40-px w-auto" wire:model.live="package_filter">
                    <option value="">{{ $lang->data['all_packages'] ?? 'All Packages' }}</option>
                    @foreach(\App\Models\Package::latest()->get() as $package)
                        <option value="{{ $package->id }}">{{ $package->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('packages.assign') }}" class="btn btn-info text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                    <iconify-icon icon="ic:outline-assignment" class="icon text-xl line-height-1"></iconify-icon>
                    {{ $lang->data['assign_package'] ?? 'Assign Package' }}
                </a>
                <a href="{{ route('packages.manage') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                    {{ $lang->data['add_new_package'] ?? 'Add New Package' }}
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ $lang->data['customer'] ?? 'Customer' }}</th>
                            <th scope="col">{{ $lang->data['package'] ?? 'Package' }}</th>
                            <th scope="col">{{ $lang->data['assigned_at'] ?? 'Assigned At' }}</th>
                            <th scope="col" class="text-center">{{ $lang->data['action'] ?? 'Action' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($assignedPackages) > 0)
                        @foreach($assignedPackages as $row)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $row->customer->name ?? '-' }}</td>
                            <td>{{ $row->package->title ?? '-' }}</td>
                            <td>{{ $row->created_at?->format('d/m/Y h:i A') }}</td>
                            <td class="text-center">
                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#editModal" wire:click="edit({{ $row->id }})" class="bg-info-100 text-info-600 bg-hover-info-200 fw-medium tw-size-8 d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                    </button>
                                    <button type="button" wire:click="delete({{ $row->id }})" class="remove-item-button bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium tw-size-8 d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                @if(count($assignedPackages) == 0)
                    <x-empty-item />
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title text-md">{{ $lang->data['edit_assigned_package'] ?? 'Edit Assigned Package' }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['customer'] ?? 'Customer' }} <span class="text-danger">*</span></label>
                            <select class="form-select radius-8" wire:model="customer_id">
                                <option value="">{{ $lang->data['select_customer'] ?? 'Select Customer' }}</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            @error('customer_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['package'] ?? 'Package' }} <span class="text-danger">*</span></label>
                            <select class="form-select radius-8" wire:model="package_id">
                                <option value="">{{ $lang->data['select_package'] ?? 'Select Package' }}</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->title }}</option>
                                @endforeach
                            </select>
                            @error('package_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-12 d-flex align-items-start justify-content-end gap-3 mt-24">
                            <button type="reset" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8" wire:click.prevent="$dispatch('closemodal')">
                                {{ $lang->data['cancel'] ?? 'Cancel' }}
                            </button>
                            <button type="submit" class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8" wire:click.prevent="update">
                                {{ $lang->data['update'] ?? 'Update' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
