{{-- Reference template — copy to resources/views/livewire/<module>/<entity>-manage.blade.php and adapt --}}
<div class="dashboard-main-body">
    <div class="card h-100 p-0 radius-12 overflow-hidden">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ $item_id ? ($lang->data['edit_item'] ?? 'Edit Item') : ($lang->data['add_item'] ?? 'Add Item') }}</h5>
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
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['some_count'] ?? 'Some Count' }} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control radius-8" wire:model="some_count" placeholder="{{ $lang->data['enter_some_count'] ?? 'Enter Some Count' }}">
                    @error('some_count') <span class="text-danger">{{ $message }}</span> @enderror
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

                {{-- Optional: related-rows checkbox table, e.g. selecting included services.
                     See laundrybox-livewire-patterns for the checkbox-array multi-select pattern
                     (wire:model.live="selected_ids" + @checked(in_array((string) $row->id, $selected_ids, true))). --}}

                <div class="col-12 d-flex align-items-center justify-content-end gap-3 mt-24">
                    <a href="{{ route('<module>.list') }}" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">{{ $lang->data['cancel'] ?? 'Cancel' }}</a>
                    @if($item_id)
                        <button type="button" class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8" wire:click.prevent="update">{{ $lang->data['update'] ?? 'Update' }}</button>
                    @else
                        <button type="button" class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8" wire:click.prevent="save">{{ $lang->data['save'] ?? 'Save' }}</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
