<?php

namespace App\Http\Livewire\Deliveryman;

use Livewire\Component;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\ProductionLine;
use App\Models\OrderSummary;
use App\Foodstock\Babel\OrderBabelized;
use App\Actions\ProductionLine\ForwardProductionProccess;
use App\Actions\ProductionLine\GenerateTrackingOrdersQr;

use App\Actions\ProductionLine\RecoverUserRestaurant;

class DeliverymanPanel extends Component
{

    protected $listeners = ['loadData'];

    public $restaurantIds;
    public $total_orders;
    public $orderSummaries = [];
    public $orderSummaryDetail;
    public $lastStepProductionLine;

    public function mount($user_id)
    {
        $this->restaurantIds = Restaurant::where("user_id", (new GenerateTrackingOrdersQr())->decode($user_id))->select("id")->get()->toArray();
        $this->lastStepProductionLine = ProductionLine::where("user_id", (new GenerateTrackingOrdersQr())->decode($user_id))
            ->where("is_active", 1)
            ->where("production_line_id", null)
            ->orderBy("step", "desc")
            ->first();
        $this->loadData();
    }    

    public function loadData(){
        $this->orderSummaries = OrderSummary::join("production_movements", "order_summaries.id", "=", "production_movements.order_summary_id")
            //->where("production_movements.production_line_id", $this->lastStepProductionLine->id)
            ->join("brokers", "brokers.id", "=", "order_summaries.broker_id")
            ->join("restaurants", "restaurants.id", "=", "order_summaries.restaurant_id")
            ->where("production_movements.step_finished", 0)
            ->whereIn("production_movements.restaurant_id", $this->restaurantIds)
            ->where("order_summaries.finalized", 0)
            ->select("order_summaries.*", "production_movements.production_line_id")
            ->selectRaw("restaurants.name as restaurant")
            ->selectRaw("brokers.name as broker")
            ->orderBy("order_summaries.friendly_number")
            ->get();
/*
        $this->orderSummaries = OrderSummary::join("production_movements", "order_summaries.id", "=", "production_movements.order_summary_id")
            //->where("production_movements.production_line_id", $this->lastStepProductionLine->id)
            ->where("production_movements.step_finished", 0)
            ->where("production_movements.restaurant_id", $this->restaurant->id)
            ->where("order_summaries.finalized", 0)
            ->select("order_summaries.*" , "production_movements.production_line_id")
            ->orderBy("order_summaries.friendly_number")
            ->get();
*/
        $this->total_orders = count($this->orderSummaries);
    }

    public function render()
    {
        $viewName = 'livewire.deliveryman.delivery';
        return view($viewName, [])->layout('layouts.public-clean');;
    }

}
