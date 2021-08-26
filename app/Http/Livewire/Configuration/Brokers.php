<?php

namespace App\Http\Livewire\Configuration;

use Livewire\Component;
use App\Models\Broker;
use App\Models\Restaurant;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class Brokers extends BaseConfigurationComponent
{
    public $brokers;
    public $restaurants;

    public function mount()
    {
        $this->wizardStep = 2;
        $this->loadData();
    }    

    public function render()
    {

        try{
            $this->brokers = Broker::where("enabled", 1)->get();
            $viewName = 'livewire.configuration.brokers';
            if($this->isWizard()) $viewName = 'livewire.configuration.wizard';
            return view($viewName)->layout('layouts.app', ['header' => 'Integrações']);
        }catch(\Exception $e){
            abort(404);
        }


    } 

    public function loadData(){
        $this->restaurants =  (new RecoverUserRestaurant())->recoverAll(auth()->user()->id);
        
    }    
}
