<?php

namespace App\Http\Livewire\Configuration\Broker;

use Livewire\Component;
use App\Models\Restaurant as RestaurantModel;
use App\Actions\ProductionLine\RecoverUserRestaurant;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;
use App\Models\Broker;

class Restaurant extends BaseConfigurationComponent
{

    public RestaurantModel $restaurant;
    public $brokers;
    public $index;

    public function mount()
    {
        $this->brokers = Broker::where("enabled", 1)->get();
    } 

    public function render()
    {
        return view('livewire.configuration.broker.restaurant');
    }
}
