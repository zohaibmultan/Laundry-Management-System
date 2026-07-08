<?php
// Reference template — copy to app/Livewire/<Module>/<Entity>List.php and adapt.

namespace App\Livewire\<Module>;

use App\Models\<Entity>;
use App\Models\Translation;
use Livewire\Attributes\Title;
use Livewire\Component;

class <Entity>List extends Component
{
    #[Title('<Entities>')]
    public $items, $search_query, $status_filter = '', $lang;

    public function mount()
    {
        if (!\Illuminate\Support\Facades\Gate::allows('<permission>')) {
            abort(404);
        }

        $this->loadItems();

        if (session()->has('selected_language')) {
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $this->lang = Translation::where('default', 1)->first();
        }
    }

    public function render()
    {
        return view('livewire.<module>.<entity>-list');
    }

    public function loadItems()
    {
        $query = <Entity>::latest();

        if ($this->status_filter !== '' && $this->status_filter !== null) {
            $query->where('status', (bool) $this->status_filter);
        }

        if ($this->search_query !== '' && $this->search_query !== null) {
            $query->where(function ($builder) {
                $builder->where('title', 'like', '%' . $this->search_query . '%')
                    ->orWhere('subtitle', 'like', '%' . $this->search_query . '%');
            });
        }

        $this->items = $query->get();
    }

    public function updated($name, $value)
    {
        if ($name === 'search_query' || $name === 'status_filter') {
            $this->loadItems();
        }
    }

    public function delete($id)
    {
        try {
            <Entity>::where('id', $id)->delete();
            $this->loadItems();
            $this->dispatch('alert', ['type' => 'success', 'message' => '<Entity> has been deleted!']);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Cannot Delete <Entity>!']);
        }
    }
}
