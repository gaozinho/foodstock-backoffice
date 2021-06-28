<?php
   
namespace App\Foodstock\Babel;
use App\Foodstock\Babel\OrderBabel;
use App\Foodstock\Babel\ItemBabel;
use App\Foodstock\Babel\Interfaces\OrderBabelInterface;

class OrderBabelized extends OrderBabel implements OrderBabelInterface
{

    public function __construct($orderBabelizedJsonString){
        parent::__construct($orderBabelizedJsonString);
    }         

    public function items(){

        $this->orderJson->items = $this->orderJson->items ?? [];

        $items = [];
        foreach($this->orderJson->items as $item){
            $itemBabel = new ItemBabel($item->name, $item->quantity, $item->unitPrice, $item->totalPrice, $item->externalCode ?? null, $item->observations ?? null);
            if(isset($item->subitems)){
                foreach($item->subitems as $option){
                    $itemBabel->addSubitem(new ItemBabel($option->name, $option->quantity, $option->unitPrice, $option->totalPrice, $option->externalCode ?? null, $option->observations ?? null));
                }
            }
            $items[] = $itemBabel;
        }
        return $items;
    }

    public function subtotal(){
        return $this->orderJson->subtotal ?? null;
    }    

    public function deliveryFee(){
        return $this->orderJson->deliveryFee ?? null;
    }    
    
    public function orderAmount(){
        return $this->orderJson->orderAmount ?? null;
    }        

    public function shortOrderNumber(){
        return $this->orderJson->shortOrderNumber ?? null;
    }

    public function createdDate(){
        return $this->orderJson->createdDate ?? null;
    }

    public function getFormattedCreatedDate(){
        return date("d/m/Y H:i:s", strtotime($this->createdDate()));
    }    

    public function ordersCountOnMerchant(){
        return $this->orderJson->ordersCountOnMerchant ?? null;
    }

    public function customerName(){
        return $this->orderJson->customerName ?? null;
    }

    public function deliveryFormattedAddress(){
        return $this->orderJson->deliveryFormattedAddress ?? null;
    }     
}