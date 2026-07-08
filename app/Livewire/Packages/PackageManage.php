<?php

namespace App\Livewire\Packages;

use App\Models\Package;
use App\Models\PackageDetail;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Models\Translation;
use Livewire\Attributes\Title;
use Livewire\Component;

class PackageManage extends Component
{
    #[Title('Package Manage')]
    public $package_id, $title, $subtitle, $items_per_week, $duration, $status = 1, $price, $lang;
    public $services = [], $selected_service_details = [];

    public function mount($id = null)
    {
        if (!\Illuminate\Support\Facades\Gate::allows('setting_view')) {
            abort(404);
        }

        $this->services = Service::where('is_active', 1)
            ->with(['details.serviceType'])
            ->latest()
            ->get();

        if ($id) {
            $package = Package::findOrFail($id);
            $this->package_id = $package->id;
            $this->title = $package->title;
            $this->subtitle = $package->subtitle;
            $this->items_per_week = $package->items_per_week;
            $this->duration = $package->duration;
            $this->status = $package->status ? 1 : 0;
            $this->price = $package->price;
            $this->selected_service_details = PackageDetail::where('package_id', $package->id)
                ->pluck('service_detail_id')
                ->map(fn ($value) => (string) $value)
                ->toArray();
        } else {
            $this->selected_service_details = $this->defaultServiceDetailIds();
        }

        if (session()->has('selected_language')) {
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $this->lang = Translation::where('default', 1)->first();
        }
    }

    public function render()
    {
        return view('livewire.packages.package-manage');
    }

    protected function defaultServiceDetailIds(): array
    {
        return ServiceDetail::whereHas('service', function ($query) {
                $query->where('is_active', 1);
            })
            ->pluck('id')
            ->map(fn ($value) => (string) $value)
            ->toArray();
    }

    public function resetInputFields()
    {
        $this->package_id = null;
        $this->title = null;
        $this->subtitle = null;
        $this->items_per_week = null;
        $this->duration = null;
        $this->status = 1;
        $this->price = null;
        $this->selected_service_details = $this->defaultServiceDetailIds();
        $this->resetErrorBag();
    }

    protected function savePackageDetails(Package $package): void
    {
        PackageDetail::where('package_id', $package->id)->delete();

        foreach ($this->selected_service_details as $serviceDetailId) {
            PackageDetail::create([
                'package_id' => $package->id,
                'service_detail_id' => $serviceDetailId,
            ]);
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'items_per_week' => 'required|integer|min:1',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'required',
        ]);

        $package = Package::create([
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'items_per_week' => $this->items_per_week,
            'duration' => $this->duration,
            'status' => $this->status ? 1 : 0,
            'price' => $this->price,
        ]);

        $this->savePackageDetails($package);

        $this->dispatch('alert', ['type' => 'success', 'message' => 'Package has been created!']);

        return redirect()->route('packages.list');
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'items_per_week' => 'required|integer|min:1',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'required',
        ]);

        $package = Package::findOrFail($this->package_id);
        $package->update([
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'items_per_week' => $this->items_per_week,
            'duration' => $this->duration,
            'status' => $this->status ? 1 : 0,
            'price' => $this->price,
        ]);

        $this->savePackageDetails($package);

        $this->dispatch('alert', ['type' => 'success', 'message' => 'Package has been updated!']);

        return redirect()->route('packages.list');
    }

    public function toggleServiceDetails($serviceId, $select)
    {
        $service = Service::with('details')->find($serviceId);
        if (!$service) return;

        $detailIds = $service->details->pluck('id')->map(fn ($value) => (string) $value)->toArray();

        if ($select) {
            $this->selected_service_details = array_unique(array_merge($this->selected_service_details, $detailIds));
        } else {
            $this->selected_service_details = array_values(array_diff($this->selected_service_details, $detailIds));
        }
    }

    public function isServiceFullySelected($serviceId): bool
    {
        $service = Service::with('details')->find($serviceId);
        if (!$service || $service->details->isEmpty()) return false;

        $detailIds = $service->details->pluck('id')->map(fn ($value) => (string) $value)->toArray();
        foreach ($detailIds as $id) {
            if (!in_array($id, $this->selected_service_details, true)) {
                return false;
            }
        }
        return true;
    }
}
