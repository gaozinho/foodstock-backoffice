<?php
   
namespace App\Foodstock\Babel;
use App\Foodstock\Babel\OrderBabel;
use App\Foodstock\Babel\ItemBabel;
use App\Foodstock\Babel\Interfaces\OrderBabelInterface;

class IfoodOrderBabel extends OrderBabel implements OrderBabelInterface
{
    public function __construct($orderString){
        parent::__construct($orderString);
    }

    public function items(){
        $items = [];
        foreach($this->orderJson->items as $item){
            $itemBabel = new ItemBabel($item->name, $item->quantity, $item->unitPrice, $item->totalPrice, $item->externalCode ?? null, $item->observations ?? null);
            if(isset($item->options)){
                foreach($item->options as $option){
                    $itemBabel->addSubitem(new ItemBabel($option->name, $option->quantity, $option->unitPrice, $option->price, $option->externalCode ?? null, $option->observations ?? null));
                }
            }
            $items[] = $itemBabel;
        }
        return $items;
    }

    

    public function brokerId(){
        return $this->orderJson->id;
    }       

    public function subtotal(){
        return $this->orderJson->total->subTotal;
    }    

    public function deliveryFee(){
        return $this->orderJson->total->deliveryFee;
    }    
    
    public function orderAmount(){
        return $this->orderJson->total->orderAmount;
    }        

    public function shortOrderNumber(){
        return $this->orderJson->displayId;
    }

    public function createdDate(){
        return $this->orderJson->createdAt;
    }

    public function getFormattedCreatedDate(){
        return date("d/m/Y H:i:s", strtotime($this->getCreatedDate()));
    }    

    public function ordersCountOnMerchant(){
        return $this->orderJson->customer->ordersCountOnMerchant;
    }

    public function customerName(){
        return $this->orderJson->customer->name;
    }

    public function deliveryFormattedAddress(){
        $addressPieces[] = $this->orderJson->delivery->deliveryAddress->formattedAddress;
        if(isset($this->orderJson->delivery->deliveryAddress->reference)) $addressPieces[] = $this->orderJson->delivery->deliveryAddress->reference;
        return implode(" :: ", $addressPieces);
    }    
}