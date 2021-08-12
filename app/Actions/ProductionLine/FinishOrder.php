<?php

namespace App\Actions\ProductionLine;

use App\Models\OrderSummary;
use Illuminate\Support\Facades\DB;
use App\Actions\ProductionLine\ForwardProductionProccess;

class FinishOrder
{
    public function finish($restaurant_id, $user_id){
        $forwardProductionProccess = new ForwardProductionProccess();
        $ordersSummaries = OrderSummary::where("restaurant_id", $restaurant_id)
            ->where("finalized", 0)->get();

        foreach($ordersSummaries as $orderSummary){
            while($forwardProductionProccess->forward($orderSummary->order_id, $user_id)){

            }
        }
    }
}