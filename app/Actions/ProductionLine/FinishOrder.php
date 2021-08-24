<?php

namespace App\Actions\ProductionLine;

use App\Models\OrderSummary;
use Illuminate\Support\Facades\DB;
use App\Actions\ProductionLine\ForwardProductionProccess;

class FinishOrder
{
    public function finish($restaurant_ids, $user_id){
        $forwardProductionProccess = new ForwardProductionProccess();
        $ordersSummaries = OrderSummary::whereIn("restaurant_id", $restaurant_ids)
            ->where("finalized", 0)->get();

        foreach($ordersSummaries as $orderSummary){
            while($forwardProductionProccess->forward($orderSummary->order_id, $user_id)){

            }
        }
    }
}