<div class="dashboard-main-body">
    <div class="card h-100 p-0 radius-12 overflow-hidden">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ $lang->data['assign_package'] ?? 'Assign Package' }}</h5>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['customer'] ?? 'Customer' }} <span class="text-danger">*</span></label>
                    <select class="form-select radius-8" wire:model="customer_id">
                        <option value="">{{ $lang->data['select_customer'] ?? 'Select Customer' }}</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} {{ $customer->phone ? '(' . $customer->phone . ')' : '' }}</option>
                        @endforeach
                    </select>
                    @error('customer_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['package'] ?? 'Package' }} <span class="text-danger">*</span></label>
                    <select class="form-select radius-8" wire:model="package_id">
                        <option value="">{{ $lang->data['select_package'] ?? 'Select Package' }}</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->title }} - {{ getFormattedCurrency($package->price) }}</option>
                        @endforeach
                    </select>
                    @error('package_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary w-100 radius-8" wire:click.prevent="assign">{{ $lang->data['assign'] ?? 'Assign' }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
