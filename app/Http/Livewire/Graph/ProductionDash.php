<?php

namespace App\Http\Livewire\Graph;

use Livewire\Component;
use App\Models\ProductionMovement;
use App\Actions\ProductionLine\RecoverUserRestaurant;
use Illuminate\Support\Facades\DB;

class ProductionDash extends Component
{
    public $productionMovements;

    protected $listeners = ['render_dash' => 'render'];
    public $selectedRestaurants;

    public function mount(){
        $this->selectedRestaurants = session('selectedRestaurants');
    }

    public function render()
    {
        $user = auth()->user();
        $restaurant_ids = is_array($this->selectedRestaurants) ? $this->selectedRestaurants : (new RecoverUserRestaurant())->recoverAllIds(auth()->user()->id)->toArray();

        $productionMovements = ProductionMovement::join("production_lines", "production_lines.id", "=", "production_movements.production_line_id")
            ->join("roles", "roles.id", "=", "production_lines.role_id")
            ->where("production_movements.step_finished", 0)
            ->where("production_lines.is_active", 1)
            ->whereIn("production_movements.restaurant_id", $restaurant_ids)
            ->groupBy("production_movements.production_line_id")
            ->select("production_lines.name", "production_lines.role_id", "production_lines.color", 
            DB::raw('count(production_movements.step_finished) as total'),
            DB::raw('roles.name as role'))
            ->orderBy("production_lines.step");

        if($user->restaurant_member == 1){
            $roles = $user->roles()->select("id")->get()->pluck("id")->toArray();
            $productionMovements->whereIn("roles.id", $roles);
        }

        $this->productionMovements = $productionMovements->get();

        return view('livewire.graph.production-dash');
    }
}
