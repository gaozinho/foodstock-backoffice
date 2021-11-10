<?php

namespace App\Http\Livewire\Printer;

use Livewire\Component;
use App\Models\OrderSummary;
use App\Models\Printer;
use App\Actions\ProductionLine\GenerateTrackingOrdersQr;

class PrintButton extends Component
{

    public $orderSummary;
    public $printer;
    public $qrUrl;
    public $doPrint = false;

    public function mount($orderSummary)
    {
        $this->orderSummary = $orderSummary;
    }

    public function render()
    {
        $qr = new GenerateTrackingOrdersQr();
        $this->printer = Printer::where('user_id', (auth()->user()->user_id ?? auth()->user()->id))->first();  
        if(is_numeric($this->orderSummary->order_id)){
            $this->qrUrl = route("order.qrcode", ["order_id" => $qr->encode($this->orderSummary->order_id), "user_id" => $qr->encode(auth()->user()->user_id ?? auth()->user()->id), "size" => 200]);
        }

        return view('livewire.printer.print-button');
    }

    public function printOrder($order_summary_id){
        $this->doPrint = true;
        $this->orderSummary = OrderSummary::find($order_summary_id);
    }
}
