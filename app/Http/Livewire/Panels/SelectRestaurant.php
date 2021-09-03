<?php

namespace App\Http\Livewire\Panels;

use Livewire\Component;
use App\Models\Restaurant;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class SelectRestaurant extends Component
{

    public $restaurants = [];
    public $selectedRestaurants;
    public $page;

    public function mount(){
        $this->selectedRestaurants = session('selectedRestaurants');
        $this->restaurants = (new RecoverUserRestaurant())->recoverAll(auth()->user()->id);
    }

    public function render()
    {
        return view('livewire.panels.select-restaurant');
    }

    public function updatedSelectedRestaurants(){
        session(['selectedRestaurants' => $this->selectedRestaurants]);
        return redirect($this->page);
    }
}
