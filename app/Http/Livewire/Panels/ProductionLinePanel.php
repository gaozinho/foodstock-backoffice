<?php

namespace App\Http\Livewire\Panels;

use Livewire\Component;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\ProductionLine;

class ProductionLinePanel extends Component
{

    public User $user;
    public $restaurantUsers;
    public $restaurant;
    public $roles;
    public $selectedRoles = [];

    protected $rules = [

    ];

    public $password_confirmation;

    protected $messages = [
        //'user.password.required' => 'O CNPJ informado é inválido.'
    ]; 

    public function mount($name)
    {
        //MVP 1 - Um restaurante por usuário
        $this->restaurant = Restaurant::where("user_id", "=", auth()->user()->id)->firstOrFail();
        $role = Role::where("name", $name)->where("guard_name", "production-line")->firstOrFail();
        $productionLine = $role->productionLines()->where("restaurant_id", $this->restaurant->id)
            ->where("is_active", 1)
            ->get();

dd($productionLine);

    }    

    public function render()
    {
        $viewName = 'livewire.panels.production-line';
        return view($viewName, [])->layout('layouts.app', ['header' => 'Equipe de trabalho']);
    }

}
