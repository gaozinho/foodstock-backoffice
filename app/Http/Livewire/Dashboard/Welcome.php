<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class Welcome extends Component
{
    public function render()
    {
        try{
            $restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);
            return view('livewire.dashboard.info');
        }catch(\Exception $e){
            return view('livewire.dashboard.welcome');
        }
    }
}
