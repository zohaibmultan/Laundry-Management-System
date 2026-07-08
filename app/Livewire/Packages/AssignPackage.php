<?php

namespace App\Livewire\Packages;

use App\Models\Customer;
use App\Models\CustomerPackage;
use App\Models\Package;
use App\Models\Translation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

class AssignPackage extends Component
{
    #[Title('Assign Package')]
    public $customer_id, $package_id, $customers, $packages, $lang;

    public function mount()
    {
        if (!\Illuminate\Support\Facades\Gate::allows('setting_view')) {
            abort(404);
        }

        $this->customers = Customer::where('is_active', 1)->latest()->get();
        $this->packages = Package::where('status', 1)->latest()->get();

        if (session()->has('selected_language')) {
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $this->lang = Translation::where('default', 1)->first();
        }
    }

    public function render()
    {
        return view('livewire.packages.assign-package');
    }

    public function assign()
    {
        $this->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id' => 'required|exists:packages,id',
        ]);

        CustomerPackage::create([
            'customer_id' => $this->customer_id,
            'package_id' => $this->package_id,
            'assigned_by' => Auth::id(),
        ]);

        $this->dispatch('alert', ['type' => 'success', 'message' => 'Package assigned successfully!']);

        $this->reset(['customer_id', 'package_id']);
    }
}
