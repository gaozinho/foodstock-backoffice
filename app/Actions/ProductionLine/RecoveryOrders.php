<?php

namespace App\Actions\ProductionLine;

use App\Models\OrderSummary;
use App\Models\Role;
use App\Models\ProductionLine;

class RecoveryOrders
{

    public $productionLines = [];

    public function recoveryByStep($restaurant_id, $step_number){
        return OrderSummary::join("production_movements", "production_movements.order_summary_id", "order_summaries.id")
            ->where("order_summaries.restaurant_id", $restaurant_id)
            ->where("order_summaries.finalized", 0)
            ->where("production_movements.step_finished", 0)
            ->where("production_movements.current_step_number", $step_number)
            ->orderBy("order_summaries.friendly_number")
            ->get();
    }

    public function recoveryByRoleName($restaurant_id, $role_name){
        $currentProductionLines = $this->findCurrentProductionLinesByRoleName($restaurant_id, $role_name);

        return OrderSummary::join("production_movements", "production_movements.order_summary_id", "order_summaries.id")
            ->where("order_summaries.restaurant_id", $restaurant_id)
            ->where("order_summaries.finalized", 0)
            ->where("production_movements.step_finished", 0)
            ->whereIn("production_movements.production_line_id", $currentProductionLines->pluck("id"))
            ->orderBy("order_summaries.friendly_number")
            ->select(['order_summaries.*', 'production_movements.production_line_id', 'production_movements.current_step_number', 'production_movements.paused'])
            ->get();
    }

    public function recoveryPreviousByRoleName($restaurant_id, $role_name){
        $currentProductionLines = $this->findCurrentProductionLinesByRoleName($restaurant_id, $role_name);
        $see_previous = false;
        $currentProductionLine = null;

        foreach($currentProductionLines as $productionLine){
            if($productionLine->see_previous == 1 && $productionLine->production_line_id == null){
                $see_previous = true;
                $currentProductionLine = $productionLine;
            } 
        }
        
        if($see_previous){
            try{
                $previousProductionLine = ProductionLine::where("restaurant_id", $currentProductionLine->restaurant_id)
                ->where("is_active", 1)
                ->where("production_line_id", null)
                ->where("step", ($currentProductionLine->step - 1))
                ->orderBy("step", "desc")
                ->firstOrFail();   

                return OrderSummary::join("production_movements", "production_movements.order_summary_id", "order_summaries.id")
                    ->where("order_summaries.restaurant_id", $previousProductionLine->restaurant_id)
                    ->where("order_summaries.finalized", 0)
                    ->where("production_movements.step_finished", 0)
                    ->where("production_movements.production_line_id", $previousProductionLine->id)
                    ->select(['order_summaries.*', 'production_movements.production_line_id', 'production_movements.current_step_number'])
                    ->orderBy("order_summaries.friendly_number")
                    ->get();

            }catch(\Exception $e){
                throw new \Exception("A linha de produção está configurada para recuperar etapas anteriores, porém, não há etapas anteriores cadasradas.");
            }
        }
        return [];
    }

    private function findCurrentProductionLinesByRoleName($restaurant_id, $role_name){
        if(count($this->productionLines) > 0) return $this->productionLines;
        $role = Role::where("name", $role_name)->where("guard_name", "production-line")->firstOrFail();  
        return $role->productionLines()->where("restaurant_id", $restaurant_id)
            ->where("is_active", 1)
            ->get();
    }

    public function getCurrentProductionLineByRoleName($restaurant_id, $role_name){
        if(count($this->productionLines) > 0) return $this->productionLines;
        $role = Role::where("name", $role_name)->where("guard_name", "production-line")->firstOrFail();  
        return $role->productionLines()->where("restaurant_id", $restaurant_id)
        ->where("is_active", 1)
        ->where("production_line_id", null)
        ->firstOrFail();
    }    

    public function getProductionLineColors($restaurant_id){
        return ProductionLine::where("restaurant_id", $restaurant_id)
        ->where("is_active", 1)
        ->get()
        ->pluck("color", "step");
    }        
}