<?php

namespace App\Actions\ProductionLine;

use App\Models\OrderSummary;

class RecoveryOrders
{
    public function recoveryByStep($restaurant_id, $step_number){
        return OrderSummary::join("production_movements", "production_movements.order_summary_id", "order_summaries.id")
        ->where("order_summaries.restaurant_id", $restaurant_id)
        ->where("order_summaries.finalized", 0)
        ->where("production_movements.step_finished", 0)
        ->where("production_movements.current_step_number", $step_number)
        ->orderBy("order_summaries.friendly_number")
        ->get();
    }
}