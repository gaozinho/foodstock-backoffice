<?php

namespace App\Http\Livewire\Stock;

use Livewire\Component;
use App\Models\StockMovement;
use App\Http\Livewire\Configuration\BaseConfigurationComponent;

class ProductCard extends BaseConfigurationComponent
{

    public $product;

    protected $rules = [
        'product.current_stock' => 'required|integer',
    ];    

    public function render()
    {
        return view('livewire.stock.product-card');
    }

    public function moveStock(){
        if(!is_numeric($this->product->current_stock)){
            $this->product->current_stock = $this->product->getOriginal('current_stock');
            $this->simpleAlert('error', 'O valor informado é inválido.');
        }else{
            $prevCurrent_stock = $this->product->getOriginal('current_stock');
            $current_stock = intval($this->product->current_stock);
            $movement_type = 0; //Retira
            $quantity = 0;
    
            if($current_stock > $prevCurrent_stock){ //Add
                $movement_type = 1;
                $quantity = $current_stock - $prevCurrent_stock;
            }else if($current_stock < $prevCurrent_stock){ //Remove
                $quantity = $prevCurrent_stock - $current_stock;
            }
    
            $this->product->save();
    
            StockMovement::create([
                'product_id' => $this->product->id,
                'restaurant_id' => $this->product->restaurant_id,
                'user_id' => auth()->user()->id, 
                'name' => $this->product->name, 
                'unit_price' => $this->product->unitPrice, 
                'movement_type' => $movement_type, 
                'quantity' => abs($quantity), 
                'unit' => $this->product->unit,
            ]);

            $this->simpleAlert('success', 'Atualizado com sucesso.');
        }
    }
}
