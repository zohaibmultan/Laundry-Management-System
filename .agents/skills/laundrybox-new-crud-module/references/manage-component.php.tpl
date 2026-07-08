<?php
// Reference template — copy to app/Livewire/<Module>/<Entity>Manage.php and adapt.

namespace App\Livewire\<Module>;

use App\Models\<Entity>;
use App\Models\Translation;
use Livewire\Attributes\Title;
use Livewire\Component;

class <Entity>Manage extends Component
{
    #[Title('<Entity> Manage')]
    public $item_id, $title, $subtitle, $some_count, $status = 1, $price, $lang;

    public function mount($id = null)
    {
        if (!\Illuminate\Support\Facades\Gate::allows('<permission>')) {
            abort(404);
        }

        if ($id) {
            $item = <Entity>::findOrFail($id);
            $this->item_id = $item->id;
            $this->title = $item->title;
            $this->subtitle = $item->subtitle;
            $this->some_count = $item->some_count;
            $this->status = $item->status ? 1 : 0;
            $this->price = $item->price;
        }

        if (session()->has('selected_language')) {
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $this->lang = Translation::where('default', 1)->first();
        }
    }

    public function render()
    {
        return view('livewire.<module>.<entity>-manage');
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'some_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'required',
        ];
    }

    public function save()
    {
        $this->validate($this->rules());

        <Entity>::create([
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'some_count' => $this->some_count,
            'status' => $this->status ? 1 : 0,
            'price' => $this->price,
        ]);

        $this->dispatch('alert', ['type' => 'success', 'message' => '<Entity> has been created!']);

        return redirect()->route('<module>.list');
    }

    public function update()
    {
        $this->validate($this->rules());

        $item = <Entity>::findOrFail($this->item_id);
        $item->update([
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'some_count' => $this->some_count,
            'status' => $this->status ? 1 : 0,
            'price' => $this->price,
        ]);

        $this->dispatch('alert', ['type' => 'success', 'message' => '<Entity> has been updated!']);

        return redirect()->route('<module>.list');
    }
}

// Note: the shipped Packages module keeps the validation array duplicated inline in both
// save() and update() rather than extracted into rules() — the extraction above is a minor,
// safe simplification. Duplicate inline instead if you want to match the existing style exactly.
