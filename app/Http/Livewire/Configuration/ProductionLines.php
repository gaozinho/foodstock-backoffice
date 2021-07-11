<?php

namespace App\Http\Livewire\Configuration;

use Livewire\Component;
use App\Models\Restaurant;
use App\Models\ProductionLine;
use App\Models\ProductionLineVersion;
use Spatie\Permission\Models\Role;
use App\Enums\ProductionLineType;
use Illuminate\Support\Facades\DB;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class ProductionLines extends BaseConfigurationComponent
{

    protected $listeners = ['productionUpdated' => 'saveProductionUpdated'];

    public $productionLines;
    public $roles;
    public $jsonProductionLines;

    public function prepareProductionLine($restaurant_id){
        $totalMovements = ProductionLineVersion::join("production_movements", "production_movements.production_line_version_id", "=", "production_line_versions.id")
            ->where("production_line_versions.restaurant_id", $restaurant_id)
            ->where("production_line_versions.is_active", 1)
            ->count();

        if($totalMovements == 0){ //Se não houve movmentos, apaga a versão definitivamente para deixar o banco mais leve.
            ProductionLine::where("restaurant_id", $restaurant_id)
                ->where("is_active", 1)
                ->delete();
        }else{
            //TODO - Se houver movimentos, coloca todos na última etapa

            ProductionLine::where("restaurant_id", $restaurant_id)->where("is_active", 1)->update(['is_active' => 0]);
        }

        ProductionLineVersion::where("restaurant_id", $restaurant_id)->where("is_active", 1)->update(['is_active' => 0]);
    }

    public function saveProductionUpdated($json)
    {

        try{
            DB::beginTransaction();
            $restaurant = $this->userRestaurant();
            $this->prepareProductionLine($restaurant->id);
            $productionLineVersion = $this->createProductionLineVersion();

            $productionLines = json_decode($json, true);

            $steps = $productionLines["step"];

            

            for($i = 0; $i < count($steps); $i++){
                $fatherProductionLineId = null;

                //Etapa pai
                if(intval($productionLines["father_step"][$i]) > 0){
                    $fatherProductionLine = ProductionLine::where("restaurant_id", $restaurant->id)
                        ->where("is_active", 1)
                        ->where("step", intval($productionLines["father_step"][$i]))->first();
                    if(is_object($fatherProductionLine)) $fatherProductionLineId = $fatherProductionLine->id;

                }

                $productionLine = [
                    "step" => $productionLines["step"][$i],
                    "color" => $productionLines["color"][$i],
                    "role_id" => intval($productionLines["role_id"][$i]),
                    "name" => $productionLines["name"][$i] == "" ? null : $productionLines["name"][$i],
                    "restaurant_id" => $restaurant->id,
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
            $this->productionLines();
            $this->emit('reloadColors');

            $this->simpleAlert('success', 'Processo atualizado com sucesso.');

            DB::commit();

            if($this->isWizard()) $this->continue('wizard.success.index');

        }catch(\Exception $exception){
            DB::rollBack();
            $this->simpleAlert('error', 'Não conseguimos processar sua requisição.');
            if(env('APP_DEBUG')) throw $exception;
        }
    }

    public function mount()
    {
        if(!auth()->user()->hasRole("admin")) return redirect()->to('/dashboard');
        $this->wizardStep = 3;

        //MVP 1 - Um restaurante por usuário
        
        $this->productionLines();
        if($this->productionLines->isEmpty()) $this->createDefaultProductionLine(false);

        $this->roles = Role::where("guard_name", ProductionLineType::RoleProductionLine)->get()->pluck("name","id");
    } 

    public function render()
    {

        $viewName = 'livewire.configuration.production-lines';
        if($this->isWizard()) $viewName = 'livewire.configuration.wizard';

        return view($viewName)
            ->layout('layouts.app', ['header' => 'Processo de produção']);
    }

    public function createDefaultProductionLine($showMessage = true){
        try{
            DB::beginTransaction();
            $restaurant = $this->userRestaurant();
            $this->prepareProductionLine($restaurant->id);
            $productionLineVersion = $this->createProductionLineVersion();
            
            
            
            /**
            $version = ProductionLine::where("restaurant_id", $restaurant->id)->max('version');
            $version = ($version == null) ? 1 : ($version + 1);
            ProductionLine::where("restaurant_id", $restaurant->id)->update(['is_active' => 0]);
            */

            //$roles = Role::where("guard_name", "=", ProductionLineType::Role);
            $productionLine = ProductionLine::create([
                "role_id" => ProductionLineType::Cozinha,
                'production_line_version_id' => $productionLineVersion->id, 
                'production_line_id' => null, 
                'restaurant_id' => $restaurant->id, 
                'name' => Role::find(ProductionLineType::Cozinha)->name, 
                'step' => 1, 
                'clickable' => false, 
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
                'restaurant_id' => $restaurant->id, 
                'name' => Role::find(ProductionLineType::Montagem)->name, 
                'step' => 2, 
                'clickable' => true, 
                'see_previous' => true, 
                'next_on_click' => true, 
                'can_pause' => true, 
                'color' => '#953330', 
                'version' => $productionLineVersion->version, 
                'is_active' => 1, 
                'created_at' => date("Y-m-d H:i:s"), 
                'updated_at' => date("Y-m-d H:i:s")
            ]);

            $productionLine = ProductionLine::create([
                "role_id" => ProductionLineType::Montagem,
                'production_line_version_id' => $productionLineVersion->id, 
                'production_line_id' => $productionLine->id, 
                'restaurant_id' => $restaurant->id, 
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

            $productionLine = ProductionLine::create([
                "role_id" => ProductionLineType::Selagem,
                'production_line_version_id' => $productionLineVersion->id, 
                'production_line_id' => null, 
                'restaurant_id' => $restaurant->id, 
                'name' => Role::find(ProductionLineType::Selagem)->name, 
                'step' => 4, 
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
                'restaurant_id' => $restaurant->id, 
                'name' => Role::find(ProductionLineType::Expedicao)->name, 
                'step' => 5, 
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

            $this->productionLines = ProductionLine::where("restaurant_id", $restaurant->id)->where("is_active", 1)->get();

            $this->emit('reloadColors');
            DB::commit();
            if($showMessage) $this->simpleAlert('success', 'Padrão restaurado.');
        }catch(\Exception $exception){
            DB::rollBack();
            $this->simpleAlert('error', 'Não conseguimos processar sua requisição.');
            if(env('APP_DEBUG')) throw $exception;
        }            
    }

    private function userRestaurant(){
        //return Restaurant::where("user_id", "=", auth()->user()->id)->firstOrFail();
        return (new RecoverUserRestaurant())->recover(auth()->user()->id);
    }

    private function productionLines(){
        $restaurant = $this->userRestaurant();
        $this->productionLines = ProductionLine::where("restaurant_id", $restaurant->id)->where("is_active", 1)->orderBy("step")->orderBy("id")->get();
    }

    private function createProductionLineVersion(){
        $restaurant = $this->userRestaurant();
        $currentVersion = ProductionLineVersion::where("restaurant_id", $restaurant->id)->max('version');
        ProductionLine::where("restaurant_id", $restaurant->id)->where("is_active", 1)->update(['is_active' => 0]);
        ProductionLineVersion::where("restaurant_id", $restaurant->id)->where("is_active", 1)->update(['is_active' => 0]);
        return ProductionLineVersion::create([
            "restaurant_id" => $restaurant->id,
            "version" => $currentVersion + 1,
            "is_active" => 1
        ]);
    }    
}
