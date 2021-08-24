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
use App\Actions\ProductionLine\RecoveryOrders;
use App\Actions\ProductionLine\RestartOrderProcess;

class ProductionLines extends BaseConfigurationComponent
{

    protected $listeners = ['productionUpdated' => 'saveProductionUpdated', 'confirmFirstStep', 'confirmDefault'];

    public $productionLines;
    public $roles;
    public $jsonProductionLines;
    public $countAlive;

    public $areThereAlive = false;

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

    public function confirmFirstStep($jsonProductionLines){
        try{
            
            $restaurant = $this->userRestaurant();
            $newProductionLineVersion = (new RestartOrderProcess())->restart($jsonProductionLines, $restaurant->id);

            $this->productionLines();
            $this->emit('reloadColors');
            $this->simpleAlert('success', 'Processo atualizado com sucesso.');
            if($this->isWizard()) $this->continue('wizard.success.index');

        }catch(\Exception $exception){
            $this->simpleAlert('error', 'Não conseguimos processar sua requisição.');
            if(env('APP_DEBUG')) throw $exception;
        }        

    }

    public function confirmDefault($message = true){
        try{
            
            $restaurant = $this->userRestaurant();
            $restartOrderProcess = new RestartOrderProcess();

            $newProductionLineVersion = $restartOrderProcess->createProductionLineVersion($restaurant->id);
            $restartOrderProcess->createDefaultProductionLine($newProductionLineVersion);

            $this->productionLines();
            $this->emit('reloadColors');
            if($message) $this->simpleAlert('success', 'Processo restaurado com sucesso.');

        }catch(\Exception $exception){
            $this->simpleAlert('error', 'Não conseguimos processar sua requisição.');
            if(env('APP_DEBUG')) throw $exception;
        }  
    }


    public function mount()
    {
        if(!auth()->user()->hasRole("admin")) return redirect()->to('/dashboard');
        $this->wizardStep = 3;

        $this->productionLines();
        if($this->productionLines->isEmpty()) $this->confirmDefault(false);

        $this->countAlive = (new RecoveryOrders())->countAlive((new RecoverUserRestaurant())->recover(auth()->user()->id)->toArray());

        $this->roles = Role::where("guard_name", ProductionLineType::RoleProductionLine)->get()->pluck("name","id");
    }


    public function render()
    {

        $viewName = 'livewire.configuration.production-lines';
        if($this->isWizard()) $viewName = 'livewire.configuration.wizard';

        return view($viewName)
            ->layout('layouts.app', ['header' => 'Processo de produção']);
    }


    private function userRestaurants(){
        //return Restaurant::where("user_id", "=", auth()->user()->id)->firstOrFail();
        return (new RecoverUserRestaurant())->recoverAll(auth()->user()->id);
    }

    private function productionLines(){
        $restaurant = $this->userRestaurant();
        $this->productionLines = ProductionLine::where("restaurant_id", $restaurant->id)->where("is_active", 1)->orderBy("step")->orderBy("id")->get();
    }
}
