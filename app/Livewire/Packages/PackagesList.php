<?php

namespace App\Livewire\Packages;

use App\Models\Package;
use App\Models\Translation;
use Livewire\Attributes\Title;
use Livewire\Component;

class PackagesList extends Component
{
    #[Title('Packages')]
    public $packages, $search_query, $status_filter = '', $lang;

    public function mount()
    {
        if (!\Illuminate\Support\Facades\Gate::allows('setting_view')) {
            abort(404);
        }

        $this->loadPackages();

        if (session()->has('selected_language')) {
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $this->lang = Translation::where('default', 1)->first();
        }
    }

    public function render()
    {
        return view('livewire.packages.packages-list');
    }

    public function loadPackages()
    {
        $query = Package::latest();

        if ($this->status_filter !== '' && $this->status_filter !== null) {
            $query->where('status', (bool) $this->status_filter);
        }

        if ($this->search_query !== '' && $this->search_query !== null) {
            $query->where(function ($builder) {
                $builder->where('title', 'like', '%' . $this->search_query . '%')
                    ->orWhere('subtitle', 'like', '%' . $this->search_query . '%');
            });
        }

        $this->packages = $query->get();
    }

    public function updated($name, $value)
    {
        if ($name === 'search_query' || $name === 'status_filter') {
            $this->loadPackages();
        }
    }

    public function delete($id)
    {
        try {
            Package::where('id', $id)->delete();
            $this->loadPackages();
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Package has been deleted!']);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Cannot Delete Package!']);
        }
    }
}
