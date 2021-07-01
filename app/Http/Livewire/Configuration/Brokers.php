<?php

namespace App\Http\Livewire\Configuration;

use Livewire\Component;
use App\Models\Broker;
use App\Models\Restaurant;
use App\Models\IfoodBroker;
use App\Enums\BrokerType;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;
use App\Integrations\IfoodIntegration;
use App\Integrations\IfoodIntegrationDistributed;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class Brokers extends BaseConfigurationComponent
{
    public function mount()
    {
        $this->wizardStep = 2;
    }    

    public function render()
    {

        try{
            $restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
            $viewName = 'livewire.configuration.brokers';
            if($this->isWizard()) $viewName = 'livewire.configuration.wizard';
            return view($viewName)->layout('layouts.app', ['header' => 'Integrações']);
        }catch(\Exception $e){
            abort(404);
        }


    } 
}
