<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title text-md" id="exampleModalLabel">{{ $lang->data['add_new_customer'] ?? 'Add New Customer' }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <form action="#">
                    <div class="row">
                        <div class="col-6 mb-20">
                            <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['customer_name'] ?? 'Customer Name' }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control radius-8" placeholder="{{ $lang->data['enter_customer_name'] ?? 'Enter Customer Name' }}" wire:model="name">
                            @error('name')
                            <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6 mb-20">
                            <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['phone_number'] ?? 'Phone Number' }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control radius-8" placeholder="{{ $lang->data['enter_phone_number'] ?? 'Enter Phone Number' }}" wire:model="phone">
                            @error('phone')
                            <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6 mb-20">
                            <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['email'] ?? 'Email' }}</label>
                            <input type="text" class="form-control radius-8" placeholder="{{ $lang->data['enter_email'] ?? 'Enter Email' }}" wire:model="email">
                            @error('email')
                            <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6 mb-20">
                            <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['tax_number'] ?? 'Tax Number' }}</label>
                            <input type="text" class="form-control radius-8" placeholder="{{ $lang->data['enter_tax_number'] ?? 'Enter Tax Number' }}" wire:model="tax_number">
                        </div>
                        <div class="col-12 mb-20">
                            <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['address'] ?? 'Address' }}</label>
                            <textarea class="form-control radius-8" placeholder="{{ $lang->data['enter_address'] ?? 'Enter Address' }}" wire:model="address"></textarea>
                        </div>
                        <div class="col-12 tw-mt-6">
                            <div class="form-switch switch-primary d-flex align-items-center gap-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="switch1" checked="" wire:model="is_active">
                                <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="switch1">{{ $lang->data['is_active'] ?? 'Is Active' }} ?</label>
                            </div>
                        </div>
                        <div class="d-flex align-items-start justify-content-end gap-3 mt-24">
                            <button type="reset" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8" wire:click.prevent="$dispatch('closemodal')">
                                {{ $lang->data['cancel'] ?? 'Cancel' }}
                            </button>
                            <button type="submit" class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8" wire:click.prevent="store">
                                {{ $lang->data['save'] ?? 'Save' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
