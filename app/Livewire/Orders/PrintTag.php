<?php

namespace App\Livewire\Orders;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Order;
use App\Models\Translation;

class PrintTag extends Component
{
    public $order, $lang;

    #[Layout('components.layouts.print-layout')]
    public function render()
    {
        return view('livewire.orders.print-tag');
    }

    public function mount($id)
    {
        if(!\Illuminate\Support\Facades\Gate::allows('order_print')){
            abort(404);
        }
        $this->order = Order::where('id', $id)->first();
        if (!$this->order) {
            abort(404);
        }
        if (session()->has('selected_language')) {
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $this->lang = Translation::where('default', 1)->first();
        }
    }
}
