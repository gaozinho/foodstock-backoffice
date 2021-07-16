<?php

namespace App\Http\Livewire\Help;

use Livewire\Component;
use App\Actions\Help\PerformHealthCheck;

class HealthCheck extends Component
{

    public $deliveryOk;
    public $integrationOk;
    public $proccessOk;
    public $merchantAvailable;
    public $brokersOk = [];
    public $everythingOk;

    public function mount(){
        $performHealthCheck = new PerformHealthCheck();
        $this->deliveryOk = $performHealthCheck->deliveryOk();
        $this->integrationOk = $performHealthCheck->integrationOk();
        $this->proccessOk = $performHealthCheck->proccessOk();
        $this->brokersOk = $performHealthCheck->brokersOk;
        $this->merchantAvailable = $performHealthCheck->merchantAvailable();

        $this->everythingOk = $this->deliveryOk && $this->integrationOk && $this->proccessOk && $this->merchantAvailable;
    }

    public function render()
    {
        return view('livewire.help.health-check');
    }
}
