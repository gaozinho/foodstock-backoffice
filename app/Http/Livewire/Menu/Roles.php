<?php

namespace App\Http\Livewire\Menu;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\Restaurant;
use App\Actions\ProductionLine\RecoveryUserRoles;

class Roles extends Component
{
    public $roles = [];

    public function mount(){
        $this->roles = (new RecoveryUserRoles())->roles();
    }

    public function render()
    {
        
        return view('livewire.menu.roles');
    }
}
