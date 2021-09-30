<?php

namespace App\Http\Livewire\Stock;

use Livewire\Component;
use App\Models\StockMovement;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;

class ProductCard extends BaseConfigurationComponent
{

    public $product;
    public $add_to_current_stock;

    protected $rules = [
        'product.current_stock' => 'required|integer',
        'add_to_current_stock' => 'min:0|max:9999|integer',
    ];    

    public function render()
    {
        return view('livewire.stock.product-card');
    }

    public function moveStock(){
        $this->product->refresh();

        if(!is_numeric($this->add_to_current_stock)){
            $this->simpleAlert('error', 'O valor informado é inválido.');
        }else{

            $prevCurrent_stock = $this->product->current_stock;
            $current_stock = intval($this->add_to_current_stock);
            $movement_type = 0; //Retira
            $quantity = 0;
    
            if($current_stock >= 0){ //Add
                $movement_type = 1;
            }

            $this->product->current_stock += $current_stock;
            $this->product->save();
    
            StockMovement::create([
                'product_id' => $this->product->id,
                'restaurant_id' => $this->product->restaurant_id,
                'user_id' => auth()->user()->id, 
                'name' => $this->product->name, 
                'unit_price' => $this->product->unitPrice, 
                'movement_type' => $movement_type, 
                'quantity' => abs($current_stock), 
                'unit' => $this->product->unit,
            ]);

            $this->simpleAlert('success', 'Atualizado com sucesso.');
        }

        $this->add_to_current_stock = "";
    }
}
