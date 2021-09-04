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

    public function finishOne($order_id){
        $forwardProductionProccess = new ForwardProductionProccess();
        while($forwardProductionProccess->forward($order_id, null)){

        }
    }    

    public function batchFinish($user_id, $order_ids){
        $user_id = auth()->user()->user_id ?? auth()->user()->id;
        $forwardProductionProccess = new ForwardProductionProccess();
        
        if(is_array($order_ids) && count($order_ids) > 0){
            foreach($order_ids as $order_id){
                while($forwardProductionProccess->forward($order_id, $user_id)){
    
                }
            }
        }

        return count($order_ids);
    }

}