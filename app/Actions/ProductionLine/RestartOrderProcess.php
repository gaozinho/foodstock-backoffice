<?php

namespace App\Actions\ProductionLine;

use App\Models\ProductionLine;
use App\Models\ProductionLineVersion;
use Illuminate\Support\Facades\DB;
use App\Enums\ProductionLineType;
use Spatie\Permission\Models\Role;

class RestartOrderProcess
{

    public function restart($json, $restaurant_id){
        try{
            DB::beginTransaction();
            //DB::statement("SET optimizer_switch = 'derived_merge=off'");
            //1 - Criar nova versão da linha de produção
            $currentProductionLineVersion = ProductionLineVersion::where("restaurant_id", $restaurant_id)->where("is_active", 1)->firstOrFail();
            $newProductionLineVersion = $this->createProductionLineVersion($restaurant_id);
            $this->createProductionLine($json, $newProductionLineVersion);
            // 2 - Volta para o primeiro passo
            $this->arrangeOrders($currentProductionLineVersion, $newProductionLineVersion);
            DB::commit();
            return $newProductionLineVersion;
        }catch(\Exception $exception){
            DB::rollBack();
            if(env('APP_DEBUG')) throw $exception;
        }        
    }

    public function restartDefault($restaurant_id){
        try{
            DB::beginTransaction();
            //DB::statement("SET optimizer_switch = 'derived_merge=off'");
            //1 - Criar nova versão da linha de produção
            $currentProductionLineVersion = ProductionLineVersion::where("restaurant_id", $restaurant_id)->where("is_active", 1)->firstOrFail();
            $newProductionLineVersion = $this->createProductionLineVersion($restaurant_id);
            $this->createDefaultProductionLine($newProductionLineVersion);
            // 2 - Volta para o primeiro passo
            $this->arrangeOrders($currentProductionLineVersion, $newProductionLineVersion);
            DB::commit();
            return $newProductionLineVersion;
        }catch(\Exception $exception){
            DB::rollBack();
            if(env('APP_DEBUG')) throw $exception;
        }        
    }  
    
    public function createDefaultProductionLine(ProductionLineVersion $productionLineVersion, $showMessage = true){
        try{
            DB::beginTransaction();

            $productionLine = ProductionLine::create([
                "role_id" => ProductionLineType::Cozinha,
                'production_line_version_id' => $productionLineVersion->id, 
                'production_line_id' => null, 
                'restaurant_id' => $productionLineVersion->restaurant_id, 
                'name' => Role::find(ProductionLineType::Cozinha)->name, 
                'step' => 1, 
                'clickable' => true, 
                'see_previous' => false, 
                'next_on_click' => false, 
                'can_pause' => false, 
                'color' => '#6c757d', 
                'version' => $productionLineVersion->version, 
                'is_active' => 1, 
                'created_at' => date("Y-m-d H:i:s"), 
                'updated_at' => date("Y-m-d H:i:s")
            ]);

            $productionLine = ProductionLine::create([
                "role_id" => ProductionLineType::Montagem,
                'production_line_version_id' => $productionLineVersion->id, 
                'production_line_id' => null, 
                'restaurant_id' => $productionLineVersion->restaurant_id, 
                'name' => Role::find(ProductionLineType::Montagem)->name, 
                'step' => 2, 
                'clickable' => true, 
                'see_previous' => false, 
                'next_on_click' => false, 
                'can_pause' => true, 
                'color' => '#953330', 
                'version' => $productionLineVersion->version, 
                'is_active' => 1, 
                'created_at' => date("Y-m-d H:i:s"), 
                'updated_at' => date("Y-m-d H:i:s")
            ]);

            /*
            $productionLine = ProductionLine::create([
                "role_id" => ProductionLineType::Montagem,
                'production_line_version_id' => $productionLineVersion->id, 
                'production_line_id' => $productionLine->id, 
                'restaurant_id' => $productionLineVersion->restaurant_id, 
                'name' => Role::find(ProductionLineType::Montagem)->name, 
                'step' => 3, 
                'clickable' => true, 
                'see_previous' => true, 
                'next_on_click' => false, 
                'can_pause' => true, 
                'color' => '#007bff', 
                'version' => $productionLineVersion->version, 
                'is_active' => 1, 
                'created_at' => date("Y-m-d H:i:s"), 
                'updated_at' => date("Y-m-d H:i:s")
            ]);  
            */

            $productionLine = ProductionLine::create([
                "role_id" => ProductionLineType::Selagem,
                'production_line_version_id' => $productionLineVersion->id, 
                'production_line_id' => null, 
                'restaurant_id' => $productionLineVersion->restaurant_id, 
                'name' => Role::find(ProductionLineType::Selagem)->name, 
                'step' => 3, 
                'clickable' => true, 
                'see_previous' => false, 
                'next_on_click' => false, 
                'can_pause' => false, 
                'color' => '#dc3545', 
                'version' => $productionLineVersion->version, 
                'is_active' => 1, 
                'created_at' => date("Y-m-d H:i:s"), 
                'updated_at' => date("Y-m-d H:i:s")
            ]);

            $productionLine = ProductionLine::create([
                "role_id" => ProductionLineType::Expedicao,
                'production_line_version_id' => $productionLineVersion->id, 
                'production_line_id' => null, 
                'restaurant_id' => $productionLineVersion->restaurant_id, 
                'name' => Role::find(ProductionLineType::Expedicao)->name, 
                'step' => 4, 
                'clickable' => true, 
                'see_previous' => false, 
                'next_on_click' => false, 
                'can_pause' => false, 
                'color' => '#28a745', 
                'version' => $productionLineVersion->version, 
                'is_active' => 1, 
                'created_at' => date("Y-m-d H:i:s"), 
                'updated_at' => date("Y-m-d H:i:s")
            ]);

            DB::commit();

            return ProductionLine::where("restaurant_id", $productionLineVersion->restaurant_id)->where("is_active", 1)->get();

        }catch(\Exception $exception){
            DB::rollBack();
            if(env('APP_DEBUG')) throw $exception;
        }            
    }    

    public function arrangeOrders(ProductionLineVersion $currentProductionLineVersion, ProductionLineVersion $newProductionLineVersion){
        // 2 - Volta para o primeiro passo

        // Itens a manter
        $keepMovements = DB::select('SELECT pm1.id
            FROM production_movements pm1
            INNER JOIN order_summaries os1 ON os1.id = pm1.order_summary_id
            WHERE pm1.id IN (	
                SELECT MIN(pm2.id) 
                FROM production_movements pm2
                GROUP BY pm2.order_id
            ) AND pm1.production_line_version_id = ' . $currentProductionLineVersion->id .' AND os1.finalized = 0');

        $lista = array_reduce($keepMovements, function($carry, $item){
            $carry[] = $item->id;
            return $carry;
        });

        if($lista == null || !is_array($lista) ) $lista = [0];

        // Apagar outros
        $nrd = DB::delete('DELETE FROM production_movements 
            WHERE id NOT IN (' . implode(",", $lista) . ') AND production_line_version_id = ' . $currentProductionLineVersion->id .' 
            AND (
                SELECT order_summaries.finalized FROM order_summaries WHERE order_summaries.id = production_movements.order_summary_id
            ) = 0');

        // 3 - Muda a versão da linha de produção

        $productionLineNextStep = $this->nextStep($newProductionLineVersion->restaurant_id, 1);

        $nrd = DB::update('UPDATE production_movements pm
            INNER JOIN order_summaries os ON os.id = pm.order_summary_id
            SET pm.production_line_id = (
                    SELECT pl1.id 
                    FROM production_lines pl1 
                    WHERE pl1.production_line_version_id = ' . $newProductionLineVersion->id . ' 
                    AND pl1.step = 1
                ), 
                pm.step_finished = 0, pm.finished_at = NULL, pm.paused = NULL, 
                pm.paused_by = NULL , 
                pm.production_line_version_id = ' . $newProductionLineVersion->id . ',
                pm.next_step_id = ' . ($productionLineNextStep ? $productionLineNextStep->id : 'null') . '
            WHERE os.finalized = 0 AND pm.production_line_version_id = ' . $currentProductionLineVersion->id);
    }

    private function nextStep($restaurant_id, $current_step){
        return ProductionLine::where("restaurant_id", $restaurant_id)->where("is_active", 1)->where("step", ($current_step + 1))->first();
    }    

    public function createProductionLine($json, ProductionLineVersion $productionLineVersion)
    {

        $productionLines = json_decode($json, true);

        $steps = $productionLines["step"];

        for($i = 0; $i < count($steps); $i++){
            $fatherProductionLineId = null;

            //Etapa pai
            if(intval($productionLines["father_step"][$i]) > 0){
                $fatherProductionLine = ProductionLine::where("restaurant_id", $productionLineVersion->restaurant_id)
                    ->where("is_active", 1)
                    ->where("step", intval($productionLines["father_step"][$i]))->first();
                if(is_object($fatherProductionLine)) $fatherProductionLineId = $fatherProductionLine->id;

            }

            $productionLine = [
                "step" => $productionLines["step"][$i],
                "color" => $productionLines["color"][$i],
                "role_id" => intval($productionLines["role_id"][$i]),
                "name" => $productionLines["name"][$i] == "" ? null : $productionLines["name"][$i],
                "restaurant_id" => $productionLineVersion->restaurant_id,
                'production_line_version_id' => $productionLineVersion->id, 
                'version' => $productionLineVersion->version, 
                "production_line_id" => $fatherProductionLineId,
                "clickable" => 0,
                "ready" => 0,
                "see_previous" => 0,
                "next_on_click" => 0,
                "can_pause" => 0,
                "is_active" => 1,
            ];
            
            foreach($productionLines["clickable"] as $clicakble) if($clicakble == $productionLines["step"][$i]) $productionLine["clickable"] = 1;
            foreach($productionLines["ready"] as $ready) if($ready == $productionLines["step"][$i]) $productionLine["ready"] = 1;
            foreach($productionLines["see_previous"] as $see_previous) if($see_previous == $productionLines["step"][$i]) $productionLine["see_previous"] = 1;
            foreach($productionLines["next_on_click"] as $next_on_click) if($next_on_click == $productionLines["step"][$i]) $productionLine["next_on_click"] = 1;
            foreach($productionLines["can_pause"] as $can_pause) if($can_pause == $productionLines["step"][$i]) $productionLine["can_pause"] = 1;

            ProductionLine::create($productionLine);
        }

        return ProductionLine::where("restaurant_id", $productionLineVersion->restaurant_id)->where("is_active", 1)->get();

    }    

    public function createProductionLineVersion($restaurant_id){
        $currentVersion = ProductionLineVersion::where("restaurant_id", $restaurant_id)->max('version');
        ProductionLine::where("restaurant_id", $restaurant_id)->where("is_active", 1)->update(['is_active' => 0]);
        ProductionLineVersion::where("restaurant_id", $restaurant_id)->where("is_active", 1)->update(['is_active' => 0]);
        return ProductionLineVersion::create([
            "restaurant_id" => $restaurant_id,
            "version" => $currentVersion + 1,
            "is_active" => 1
        ]);
    }      

}
