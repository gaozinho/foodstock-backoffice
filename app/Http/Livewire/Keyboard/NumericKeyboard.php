<?php

namespace App\Http\Livewire\Keyboard;

use Livewire\Component;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\ProductionLine;
use App\Models\ProductionMovement;
use App\Models\OrderSummary;
use App\Foodstock\Babel\OrderBabelized;
use App\Actions\ProductionLine\ForwardProductionProccess;
use App\Actions\ProductionLine\GenerateTrackingOrdersQr;

use App\Actions\ProductionLine\RecoverUserRestaurant;

class NumericKeyboard extends Component
{

    public $restaurant;
    public $restaurantIds;
    public $orderSummaryDetail;
    public $productionLine;

    protected $listeners = ['orderDetail'];

    public function mount()
    {
        $this->restaurantIds = (new RecoverUserRestaurant())->recoverAllIds(auth()->user()->id);
        $this->orderSummaryDetail = new OrderSummary();
    }    


    public function render()
    {
        $viewName = 'livewire.keyboard.numeric-keyboard';
        return view($viewName, []);
    }

    public function orderDetail($order_number){
        $this->orderSummaryDetail = $this->prepareOrderSummary($order_number);   
        if($this->orderSummaryDetail){
            $productionMovement = ProductionMovement::where("order_summary_id", $this->orderSummaryDetail->id)
                ->where("step_finished", 0)
                ->orderBy("current_step_number")
                ->firstOrFail();
            $this->productionLine = ProductionLine::find($productionMovement->production_line_id);
        }

        $this->emit('openOrderModal');
    }    

    private function prepareOrderSummary($order_number){
        //$orderSummary = OrderSummary::findOrFail($order_summary_id);
        try{
        $orderSummary =  OrderSummary::where("friendly_number", $order_number)
            ->whereIn("restaurant_id", $this->restaurantIds)
            ->where("finalized", 0)
            ->firstOrFail();
        }catch(\Exception $e){
            $order_number = 'Pedido ' . implode(",", $order_number) . ' não encontrado.';
            $this->alert('error', ($order_number), [
                'position' =>  'top-end', 
                'timer' =>  5000,  
                'toast' =>  true, 
                'text' =>  '', 
                'confirmButtonText' =>  'Ok', 
                'cancelButtonText' =>  'Cancel', 
                'showCancelButton' =>  false, 
                'showConfirmButton' =>  false, 
            ]);

            return false;
        }

        $orderSummary->orderBabelized = new OrderBabelized($orderSummary->order_json);
        return $orderSummary;
    }

}
