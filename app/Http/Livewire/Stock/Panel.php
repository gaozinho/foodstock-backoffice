<?php

namespace App\Http\Livewire\Stock;

use Livewire\Component;
use App\Models\Product;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class Panel extends Component
{

    public $products;
    protected $listeners = ['loadData'];

    public function render()
    {
        $this->loadData();
        return view('livewire.stock.panel');
    }    

    public function loadData(){
        $selectedRestaurants = session('selectedRestaurants');
        $products = Product::where("deleted", 0)
            ->where('monitor_stock', 1)
            ->where("user_id", auth()->user()->user_id ?? auth()->user()->id);

        if(is_array($selectedRestaurants) && count($selectedRestaurants) > 0){
            //$products->whereIn("restaurant_id", $selectedRestaurants);
        }else{
            //$restaurant_ids = (new RecoverUserRestaurant())->recoverAllIds(auth()->user()->id)->toArray();
            //$products->whereIn("restaurant_id", $restaurant_ids);
        }

        $products->orderByRaw("current_stock < minimun_stock desc, name");

        $this->products = $products->get();
    }
}
