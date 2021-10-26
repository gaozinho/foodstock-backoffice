<?php

namespace App\Http\Livewire\Printer;

use Livewire\Component;

class OrderPrint extends Component
{

    public $printer = null;

    public function render()
    {
        return view('livewire.printer.order-print');
    }

    public function updatedPrinter($value)
    {
        dd($value);
    }
}
