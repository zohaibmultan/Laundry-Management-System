<div>
    @php
        $printer_type = getPrinterType();
    @endphp

    {{-- A4 --}}
    @if ($printer_type == 1)
        @include('livewire.orders.print.a4')
    @endif

    {{-- Thermal --}}
    @if ($printer_type == 2)
        @include('livewire.orders.prints.the')
    @endif


    {{-- 80mm --}}
    @if ($printer_type == 3)
        @include('livewire.orders.prints.the80')
    @endif


    {{-- 50 --}}
    @if ($printer_type == 4)
        @include('livewire.orders.prints.the50')
    @endif
</div>
