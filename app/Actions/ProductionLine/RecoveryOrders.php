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
            //->join("brokers", "brokers.id", "=", "order_summaries.broker_id")
            ->join("restaurants", "restaurants.id", "=", "order_summaries.restaurant_id")                       
            ->where("order_summaries.restaurant_id", $restaurant_id)
            ->where("order_summaries.finalized", 0)
            ->where("production_movements.step_finished", 0)
            ->where("production_movements.current_step_number", $step_number)
            ->select(['order_summaries.*'])
            ->selectRaw("restaurants.name as restaurant")
            //->selectRaw("brokers.name as broker")              
            ->orderBy("order_summaries.friendly_number")
            ->get();
    }

    public function countAlive($restaurant_ids){
        return OrderSummary::whereIn("order_summaries.restaurant_id", $restaurant_ids)
            ->where("order_summaries.finalized", 0)
            ->count();
    }    

    public function alive($restaurant_ids){
        return OrderSummary::whereIn("order_summaries.restaurant_id", $restaurant_ids)
            ->where("order_summaries.finalized", 0)
            ->get();
    }    

    public function recoveryByRoleName($restaurant_ids, $user_id, $role_name){

        $currentProductionLines = $this->findCurrentProductionLinesByRoleName($user_id, $role_name);

        return OrderSummary::join("production_movements", "production_movements.order_summary_id", "order_summaries.id")
            //->join("brokers", "brokers.id", "=", "order_summaries.broker_id")
            ->join("restaurants", "restaurants.id", "=", "order_summaries.restaurant_id")            
            ->whereIn("order_summaries.restaurant_id", $restaurant_ids)
            ->where("order_summaries.finalized", 0)
            ->where("production_movements.step_finished", 0)
            ->whereIn("production_movements.production_line_id", $currentProductionLines->pluck("id"))
            ->orderBy("order_summaries.created_at")
            ->select(['order_summaries.*', 'production_movements.production_line_id', 'production_movements.current_step_number', 'production_movements.paused'])
            ->selectRaw("restaurants.name as restaurant")
            //->selectRaw("brokers.name as broker")            
            ->get();
    }

    public function recoveryPreviousByRoleName($user_id, $role_name){
        $currentProductionLines = $this->findCurrentProductionLinesByRoleName($user_id, $role_name);
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
                $previousProductionLine = ProductionLine::where("user_id", $currentProductionLine->user_id)
                ->where("is_active", 1)
                ->where("production_line_id", null)
                ->where("step", ($currentProductionLine->step - 1))
                ->orderBy("step", "desc")
                ->firstOrFail();   

                return OrderSummary::join("production_movements", "production_movements.order_summary_id", "order_summaries.id")
                    //->join("brokers", "brokers.id", "=", "order_summaries.broker_id")
                    ->join("restaurants", "restaurants.id", "=", "order_summaries.restaurant_id")                       
                    //->where("order_summaries.restaurant_id", $previousProductionLine->restaurant_id)
                    ->where("order_summaries.finalized", 0)
                    ->where("production_movements.step_finished", 0)
                    ->where("production_movements.production_line_id", $previousProductionLine->id)
                    ->select(['order_summaries.*', 'production_movements.production_line_id', 'production_movements.current_step_number'])
                    ->selectRaw("restaurants.name as restaurant")
                    //->selectRaw("brokers.name as broker")    
                    ->orderBy("order_summaries.created_at")                    
                    ->get();

            }catch(\Exception $e){
                throw new \Exception("A linha de produção está configurada para recuperar etapas anteriores, porém, não há etapas anteriores cadasradas.");
            }
        }
        return [];
    }

    private function findCurrentProductionLinesByRoleName($user_id, $role_name){
        if(count($this->productionLines) > 0) return $this->productionLines;
        $role = Role::where("name", $role_name)->where("guard_name", "production-line")->firstOrFail();  
        return $role->productionLines()->where("user_id", $user_id)
            ->where("is_active", 1)
            ->get();
    }

    public function getCurrentProductionLineByRoleName($user_id, $role_name){
        if(count($this->productionLines) > 0) return $this->productionLines;
        $role = Role::where("name", $role_name)->where("guard_name", "production-line")->firstOrFail();  
        return $role->productionLines()->where("user_id", $user_id)
        ->where("is_active", 1)
        ->where("production_line_id", null)
        ->firstOrFail();
    }    

    public function getProductionLineColors($user_id){
        return ProductionLine::where("user_id", $user_id)
        ->where("is_active", 1)
        ->get()
        ->pluck("color", "step");
    }        
}