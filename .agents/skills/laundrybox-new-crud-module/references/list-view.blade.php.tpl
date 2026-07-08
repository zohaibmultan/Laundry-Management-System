{{-- Reference template — copy to resources/views/livewire/<module>/<entity>-list.blade.php and adapt --}}
<div class="dashboard-main-body">
    <div class="card h-100 p-0 radius-12">
        <div class="tw-py-1.5 tw-px-3 bg-base d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <form class="navbar-search d-flex gap-2 align-items-center">
                    <input type="text" class="bg-base h-40-px w-auto" placeholder="{{ $lang->data['search_here'] ?? 'Search Here' }}" wire:model.live="search_query">
                    <select class="form-select h-40-px w-auto" wire:model.live="status_filter">
                        <option value="">{{ $lang->data['all_status'] ?? 'All Status' }}</option>
                        <option value="1">{{ $lang->data['active'] ?? 'Active' }}</option>
                        <option value="0">{{ $lang->data['inactive'] ?? 'Inactive' }}</option>
                    </select>
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @can('<permission>')
                <a href="{{ route('<module>.manage') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                    {{ $lang->data['add_new'] ?? 'Add New' }}
                </a>
                @endcan
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ $lang->data['title'] ?? 'Title' }}</th>
                            {{-- ...more columns matching the entity's fields... --}}
                            <th scope="col">{{ $lang->data['status'] ?? 'Status' }}</th>
                            <th scope="col" class="text-center">{{ $lang->data['action'] ?? 'Action' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($items) > 0)
                        @foreach ($items as $item)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $item->title }}</td>
                            <td>
                                @if($item->status)
                                    <span class="text-sm fw-semibold text-success-600 bg-success-100 tw-px-2 tw-py-0.5 tw-rounded-lg text-white">{{ $lang->data['active'] ?? 'Active' }}</span>
                                @else
                                    <span class="text-sm fw-semibold text-danger-600 tw-bg-red-100 tw-px-2 tw-py-0.5 tw-rounded-lg text-white">{{ $lang->data['inactive'] ?? 'InActive' }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                    @can('<permission>')
                                    <a href="{{ route('<module>.manage', $item->id) }}" class="tw-bg-blue-100 tw-text-blue-600 hover:tw-bg-blue-300 fw-medium tw-size-8 d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                    </a>
                                    <button type="button" wire:click="delete({{ $item->id }})" class="remove-item-button bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium tw-size-8 d-flex justify-content-center align-items-center rounded-circle">
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
        </div>
    </div>
</div>
