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

    public function render()
    {
        $restaurant = (new RecoverUserRestaurant())->recover(auth()->user()->id);

        $this->productionMovements = ProductionMovement::join("production_lines", "production_lines.id", "=", "production_movements.production_line_id")
            ->where("production_movements.step_finished", 0)
            ->where("production_movements.restaurant_id", $restaurant->id)
            ->groupBy("production_movements.production_line_id")
            ->select("production_lines.name", "production_lines.role_id", "production_lines.color", DB::raw('count(production_movements.step_finished) as total'))
            ->orderBy("production_lines.step")
            ->get();

        return view('livewire.graph.production-dash');
    }
}
