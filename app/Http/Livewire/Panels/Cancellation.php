<?php

namespace App\Http\Livewire\Panels;

use Livewire\Component;
use App\Models\OrderSummary;
use App\Models\CancellationReason;
use App\Foodstock\Babel\OrderBabelized;
use App\Actions\ProductionLine\RecoverUserRestaurant;
use App\Events\CancellationRequested;
use App\Models\IfoodBroker;

class Cancellation extends Component
{
    public $orderSummaryId;
    public $cancellation_code;
    public $orderSummaryDetail;
    public $restaurant;
    public $cancellation_requested = 0;

    public function render()
    {
        return view('livewire.panels.cancellation');
    }

    public function mount($orderSummaryId){
        $this->orderSummaryId = $orderSummaryId;
        try{
            $this->restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
            $this->orderSummaryDetail = $this->prepareOrderSummary($orderSummaryId);
        }catch(\Exception $e){
            session()->flash('error', 'Você não está vinculado a um delivery. Solicite ao responsável a sua correta vinculação.');
            return redirect()->route('dashboard');
        }        
    }

    public function cancellationRequest(){
        $this->orderSummaryDetail = $this->prepareOrderSummary($this->orderSummaryId);
        CancellationRequested::dispatch(
            $this->orderSummaryDetail->orderBabelized, 
            IfoodBroker::where("restaurant_id", $this->orderSummaryDetail->restaurant_id)->firstOrFail(),
            "Reason",
            $this->cancellation_code
        );

        $orderSummary = OrderSummary::find($this->orderSummaryId);
        $this->orderSummaryDetail->cancellation_requested = $orderSummary->cancellation_requested = 1;
        $orderSummary->save();

        $this->cancellation_requested = 1;

        $this->alert('success', 'A solicitação de cancelamento foi enviada. Aguarde confirmação do marketplace.', [
            'position' =>  'top-end', 
            'timer' =>  3000,  
            'toast' =>  true, 
            'text' =>  '', 
            'confirmButtonText' =>  'Ok', 
            'cancelButtonText' =>  'Cancel', 
            'showCancelButton' =>  false, 
            'showConfirmButton' =>  false, 
        ]);

        //$this->loadData();
        //$this->emit('closeOrderModal');
    }    

    private function prepareOrderSummary($order_summary_id){
        $orderSummary =  OrderSummary::join("production_movements", "production_movements.order_summary_id", "order_summaries.id")
            ->where("order_summaries.id", $order_summary_id)
            ->where("order_summaries.restaurant_id", $this->restaurant->id)
            ->select(['order_summaries.*', 'production_movements.paused'])
            ->orderBy("production_movements.id", "desc")
            ->firstOrFail();
        $orderSummary->orderBabelized = new OrderBabelized($orderSummary->order_json);
        return $orderSummary;
    }    
}
