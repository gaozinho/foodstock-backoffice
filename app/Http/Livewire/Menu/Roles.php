<?php

namespace App\Http\Livewire\Menu;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\Restaurant;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class Roles extends Component
{
    public $roles = [];

    public function mount(){
        $user = Auth::user();
        //MVP 1 - Um restaurante por usuário
        //$restaurant = (new RecoverUserRestaurant())->recoverOrNew(auth()->user()->id);
        //$restaurant = Restaurant::where("user_id", "=", auth()->user()->id)->firstOrNew();

        if($user->hasRole('admin')){
            $this->roles = Role::join("production_lines", "production_lines.role_id", "=", "roles.id")
                ->where("production_lines.is_active", 1)
                ->where("production_lines.production_line_id", null)
                ->where("production_lines.user_id", $user->id)
                ->where("roles.guard_name", "production-line")
                ->select(["roles.*", 'production_lines.name as custom_name'])
                ->orderBy("production_lines.step")
                ->get();
        }else{
            $roles = $user->roles()->select("roles.id")->get()->pluck("id")->toArray(); 
            $this->roles = Role::join("production_lines", "production_lines.role_id", "=", "roles.id")
                ->where("production_lines.is_active", 1)
                ->where("production_lines.production_line_id", null)
                ->where("production_lines.user_id", $user->user_id)
                //->where("roles.guard_name", "production-line")
                ->whereIn("roles.id", $roles)
                ->select(["roles.*", 'production_lines.name as custom_name'])
                ->orderBy("production_lines.step")
                ->get();
                    
        }
    }

    public function render()
    {
        
        return view('livewire.menu.roles');
    }
}
