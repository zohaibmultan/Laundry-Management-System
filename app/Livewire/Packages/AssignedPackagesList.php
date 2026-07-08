<?php

namespace App\Livewire\Packages;

use App\Models\CustomerPackage;
use App\Models\Translation;
use Livewire\Attributes\Title;
use Livewire\Component;

class AssignedPackagesList extends Component
{
    #[Title('Assigned Packages')]
    public $assignedPackages, $search_query, $customer_filter = '', $package_filter = '', $lang;
    public $editMode = false, $assignedPackage, $customer_id, $package_id, $customers, $packages;

    public function mount()
    {
        if (!\Illuminate\Support\Facades\Gate::allows('setting_view')) {
            abort(404);
        }

        $this->customers = \App\Models\Customer::where('is_active', 1)->latest()->get();
        $this->packages = \App\Models\Package::where('status', 1)->latest()->get();
        $this->loadAssignedPackages();

        if (session()->has('selected_language')) {
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $this->lang = Translation::where('default', 1)->first();
        }
    }

    public function render()
    {
        return view('livewire.packages.assigned-packages-list');
    }

    public function loadAssignedPackages()
    {
        $query = CustomerPackage::with(['customer', 'package'])->latest();

        if ($this->search_query !== '' && $this->search_query !== null) {
            $query->whereHas('customer', function ($builder) {
                $builder->where('name', 'like', '%' . $this->search_query . '%');
            })->orWhereHas('package', function ($builder) {
                $builder->where('title', 'like', '%' . $this->search_query . '%');
            });
        }

        if ($this->customer_filter !== '' && $this->customer_filter !== null) {
            $query->where('customer_id', $this->customer_filter);
        }

        if ($this->package_filter !== '' && $this->package_filter !== null) {
            $query->where('package_id', $this->package_filter);
        }

        $this->assignedPackages = $query->get();
    }

    public function updated($name, $value)
    {
        if (in_array($name, ['search_query', 'customer_filter', 'package_filter'], true)) {
            $this->loadAssignedPackages();
        }
    }

    public function delete($id)
    {
        CustomerPackage::where('id', $id)->delete();
        $this->loadAssignedPackages();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Assigned package has been deleted!']);
    }

    public function resetInputFields()
    {
        $this->editMode = false;
        $this->assignedPackage = null;
        $this->customer_id = '';
        $this->package_id = '';
        $this->resetErrorBag();
    }

    public function edit($id)
    {
        $this->resetInputFields();
        $this->editMode = true;
        $this->assignedPackage = CustomerPackage::findOrFail($id);
        $this->customer_id = $this->assignedPackage->customer_id;
        $this->package_id = $this->assignedPackage->package_id;
    }

    public function update()
    {
        $this->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id' => 'required|exists:packages,id',
        ]);

        $this->assignedPackage->update([
            'customer_id' => $this->customer_id,
            'package_id' => $this->package_id,
        ]);

        $this->loadAssignedPackages();
        $this->resetInputFields();
        $this->dispatch('closemodal');
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Assigned package has been updated!']);
    }
}
