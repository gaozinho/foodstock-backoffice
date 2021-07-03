<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class Welcome extends Component
{
    public function render()
    {
        return view('livewire.dashboard.welcome');
    }
}
