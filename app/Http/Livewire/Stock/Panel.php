<?php

namespace App\Http\Livewire\Stock;

use Livewire\Component;
use App\Models\Product;
use App\Models\StockPanel;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class Panel extends Component
{

    public $products;
    public $stock_panels;
    public $selected_panels = [];
    protected $listeners = ['loadData'];

    public function render()
    {
        $this->loadData();
        return view('livewire.stock.panel');
    }    

    public function loadData(){

        $user_id = auth()->user()->user_id ?? auth()->user()->id;

        $this->stock_panels = StockPanel::join("stock_panels_has_products", "stock_panels.id", "=", "stock_panels_has_products.stock_panel_id")
            ->where(function($query) use ($user_id){
                $query->where("stock_panels.user_id", $user_id)
                    ->orWhere("stock_panels.user_id", null);
            })->orderBy("stock_panels.name")
            ->select("stock_panels.*")
            ->distinct("stock_panels.id")
            ->get();

        $products = Product::where("products.deleted", 0)
            ->where('products.monitor_stock', 1)
            ->where("products.user_id", $user_id)
            ->orderByRaw("products.current_stock < products.minimun_stock desc, products.name")
            ->select("products.*");

        if(count($this->selected_panels) > 0){
            $products->join("stock_panels_has_products", "products.id", "=", "stock_panels_has_products.product_id")
                ->whereIn("stock_panels_has_products.stock_panel_id", $this->selected_panels)
                ->distinct("products.id");
        }

        $this->products = $products->get();
        
    }    

    public function updatedSelectedPanels(){

        $this->selected_panels = array_filter($this->selected_panels, fn($value) => intval($value) > 0);
        $this->emit('stopLoading');
    }    
}
