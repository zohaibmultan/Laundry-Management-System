<?php

namespace App\Livewire\Settings;

use App\Models\MasterSettings;
use App\Models\Translation;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;

class PrinterSettings extends Component
{
    public $invoice_printer;

    public $invoice_printer_mode;

    public $cloth_tag_printer;

    public $cloth_tag_mode;

    public $lang;

    public $cert;

    #[Title('Printer Settings')]
    public function render()
    {
        return view('livewire.settings.printer-settings');
    }

    public function mount()
    {
        if (! Gate::allows('setting_master')) {
            abort(404);
        }

        $certPath = storage_path('app/qz/digital-certificate.txt');
        if (file_exists($certPath)) {
            $this->cert = file_get_contents($certPath);
        } else {
            $this->cert = '';
        }

        $settings = new MasterSettings;
        $site = $settings->siteData();

        $this->invoice_printer = (isset($site['invoice_printer']) && ! empty($site['invoice_printer'])) ? $site['invoice_printer'] : '';
        $this->invoice_printer_mode = (isset($site['invoice_printer_mode']) && ! empty($site['invoice_printer_mode'])) ? $site['invoice_printer_mode'] : 'standard';
        $this->cloth_tag_printer = (isset($site['cloth_tag_printer']) && ! empty($site['cloth_tag_printer'])) ? $site['cloth_tag_printer'] : '';
        $this->cloth_tag_mode = (isset($site['cloth_tag_mode']) && ! empty($site['cloth_tag_mode'])) ? $site['cloth_tag_mode'] : 'standard';

        if (session()->has('selected_language')) {
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $this->lang = Translation::where('default', 1)->first();
        }
    }

    public function save()
    {
        if (! Gate::allows('setting_master')) {
            abort(404);
        }

        $settings = new MasterSettings;
        $site = $settings->siteData();

        $site['invoice_printer'] = $this->invoice_printer;
        $site['invoice_printer_mode'] = $this->invoice_printer_mode;
        $site['cloth_tag_printer'] = $this->cloth_tag_printer;
        $site['cloth_tag_mode'] = $this->cloth_tag_mode;

        foreach ($site as $key => $value) {
            MasterSettings::updateOrCreate(['master_title' => $key], ['master_value' => $value]);
        }

        $this->dispatch(
            'alert',
            ['type' => 'success', 'message' => 'Printer Settings Updated Successfully!']
        );
    }
}
