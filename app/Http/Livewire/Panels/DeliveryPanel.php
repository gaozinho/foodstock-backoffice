<?php

namespace App\Http\Livewire\Panels;

use Livewire\Component;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\ProductionLine;
use App\Models\OrderSummary;
use App\Models\ProductionMovement;
use App\Foodstock\Babel\OrderBabelized;
use App\Actions\ProductionLine\ForwardProductionProccess;

use App\Actions\ProductionLine\RecoverUserRestaurant;

class DeliveryPanel extends Component
{

    protected $listeners = ['loadData'];

    public $restaurant;
    public $total_orders;
    public $orderSummaries = [];
    public $orderSummaryDetail;
    public $lastStepProductionLine;

    public function mount()
    {
        $this->restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);

        $this->lastStepProductionLine = ProductionLine::where("restaurant_id", $this->restaurant->id)
            ->where("is_active", 1)
            ->where("production_line_id", null)
            ->orderBy("step", "desc")
            ->firstOrFail();

        $this->loadData();
    }    

    public function loadData(){
        $this->orderSummaries = OrderSummary::join("production_movements", "order_summaries.id", "=", "production_movements.order_summary_id")
            //->where("production_movements.production_line_id", $this->lastStepProductionLine->id)
            ->where("production_movements.step_finished", 0)
            ->where("order_summaries.finalized", 0)
            ->select("order_summaries.*" , "production_movements.production_line_id")
            ->orderBy("order_summaries.friendly_number")
            ->get();

        $this->total_orders = count($this->orderSummaries);
    }

    public function orderDetail($order_summary_id, $production_line_id){
        $this->orderSummaryDetail = $this->prepareOrderSummary($order_summary_id);
        $productionLine = ProductionLine::findOrFail($production_line_id);

        $this->emit('openOrderModal');
        
        $this->loadData();
    } 

    public function finishProcess($order_summary_id)
    {
        $orderSummary = OrderSummary::findOrFail($order_summary_id);
        (new ForwardProductionProccess())->forward($orderSummary->order_id, auth()->user()->id);
        $this->loadData();
        $this->emit('closeOrderModal');
    }    
    
    private function prepareOrderSummary($order_summary_id){
        $orderSummary =  OrderSummary::join("production_movements", "production_movements.order_summary_id", "order_summaries.id")
            ->where("order_summaries.id", $order_summary_id)
            ->select(['order_summaries.*', 'production_movements.paused', 'production_movements.production_line_id'])
            ->orderBy("production_movements.id", "desc")
            ->firstOrFail();
        $orderSummary->orderBabelized = new OrderBabelized($orderSummary->order_json);
        return $orderSummary;
    }    

    public function render()
    {
        $viewName = 'livewire.panels.delivery';
        return view($viewName, []);
    }

}
