<div class="dashboard-main-body">
    <div class="card h-100 p-0 radius-12 overflow-hidden">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ $package_id ? ($lang->data['edit_package'] ?? 'Edit Package') : ($lang->data['add_package'] ?? 'Add Package') }}</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['title'] ?? 'Title' }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control radius-8" wire:model="title" placeholder="{{ $lang->data['enter_title'] ?? 'Enter Title' }}">
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['subtitle'] ?? 'Subtitle' }}</label>
                    <input type="text" class="form-control radius-8" wire:model="subtitle" placeholder="{{ $lang->data['enter_subtitle'] ?? 'Enter Subtitle' }}">
                    @error('subtitle') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['items_per_week'] ?? 'Items Per Week' }} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control radius-8" wire:model="items_per_week" placeholder="{{ $lang->data['enter_items_per_week'] ?? 'Enter Items Per Week' }}">
                    @error('items_per_week') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['duration'] ?? 'Duration' }} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control radius-8" wire:model="duration" placeholder="{{ $lang->data['enter_duration'] ?? 'Enter Duration' }}">
                    @error('duration') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['price'] ?? 'Price' }} <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control radius-8" wire:model="price" placeholder="{{ $lang->data['enter_price'] ?? 'Enter Price' }}">
                    @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-12">
                    <div class="form-switch switch-primary d-flex align-items-center gap-3">
                        <input class="form-check-input" type="checkbox" role="switch" wire:model="status" @checked($status)>
                        <label class="form-check-label line-height-1 fw-medium text-secondary-light">{{ $lang->data['status'] ?? 'Status' }}</label>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <div class="card border border-neutral-200 radius-8 shadow-none">
                        <div class="card-header py-3 px-4">
                            <h6 class="mb-0">{{ $lang->data['service_details'] ?? 'Service Details' }}</h6>
                        </div>
                        <div class="card-body p-24">
                            <div class="row g-4">
                                @forelse ($services as $service)
                                    @if ($service->details->isNotEmpty())
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card h-100 border border-neutral-200 shadow-none radius-12" style="display: flex; flex-direction: column;">
                                                <div class="card-header bg-neutral-100 py-12 px-16 d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold text-primary-light text-sm">{{ $service->service_name }}</span>
                                                    <div class="form-switch switch-primary d-flex align-items-center gap-2">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="select_all_{{ $service->id }}"
                                                            wire:click="toggleServiceDetails({{ $service->id }}, $event.target.checked)"
                                                            @checked($this->isServiceFullySelected($service->id))>
                                                        <label class="form-check-label text-xs fw-semibold text-secondary-light" for="select_all_{{ $service->id }}">Select All</label>
                                                    </div>
                                                </div>
                                                <div class="card-body p-16" style="max-height: 250px; overflow-y: auto; flex-grow: 1;">
                                                    <div style="display: flex; flex-direction: column; gap: 12px;">
                                                        @foreach ($service->details as $detail)
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="{{ $detail->id }}" id="detail_{{ $detail->id }}"
                                                                        wire:model.live="selected_service_details" @checked(in_array((string) $detail->id, $selected_service_details, true))>
                                                                    <label class="form-check-label text-sm text-secondary-light" for="detail_{{ $detail->id }}">
                                                                        {{ $detail->serviceType?->service_type_name ?? '-' }}
                                                                    </label>
                                                                </div>
                                                                <span class="text-xs text-primary fw-medium">{{ number_format($detail->service_price, 2) }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="col-12 text-center py-4 text-secondary-light">
                                        {{ $lang->data['no_data_found'] ?? 'No data found' }}
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex align-items-center justify-content-end gap-3 mt-24">
                    <a href="{{ route('packages.list') }}" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">{{ $lang->data['cancel'] ?? 'Cancel' }}</a>
                    @if($package_id)
                        <button type="button" class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8" wire:click.prevent="update">{{ $lang->data['update'] ?? 'Update' }}</button>
                    @else
                        <button type="button" class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8" wire:click.prevent="save">{{ $lang->data['save'] ?? 'Save' }}</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
