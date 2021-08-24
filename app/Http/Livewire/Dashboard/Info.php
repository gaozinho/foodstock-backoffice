<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class Info extends Component
{
    public function render()
    {
        try{
            return view('livewire.dashboard.info');
        }catch(\Exception $e){
            return view('livewire.dashboard.welcome');
        }
    }
}
