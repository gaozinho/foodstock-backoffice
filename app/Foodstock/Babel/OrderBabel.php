<?php
   
namespace App\Foodstock\Babel;
use App\Foodstock\Babel\ItemBabel;
use App\Foodstock\Babel\Interfaces\OrderBabelInterface;

class OrderBabel
{
    protected $orderString = null;
    protected $orderJson = null;

    public $items, $subtotal, $deliveryFee, $orderAmount, $shortOrderNumber,
        $createdDate, $ordersCountOnMerchant, $customerName, $deliveryFormattedAddress;

    public function __construct($orderString){
        if($this->isJson($orderString)){
            $this->orderString = $orderString;
            $this->orderJson = json_decode($orderString);

            $this->items = $this->items(); 
            $this->subtotal = $this->subtotal(); 
            $this->deliveryFee = $this->deliveryFee(); 
            $this->orderAmount = $this->orderAmount(); 
            $this->shortOrderNumber = $this->shortOrderNumber(); 
            $this->createdDate = $this->createdDate(); 
            $this->ordersCountOnMerchant = $this->ordersCountOnMerchant(); 
            $this->customerName = $this->customerName(); 
            $this->deliveryFormattedAddress = $this->deliveryFormattedAddress(); 
        }
    }         

    public function toString(){
        return json_encode($this);
    }    

    public function toJson(){
        return json_decode($this->toString());
    }

    public function toArray(){
        return (array) $this;
    }   

    public function orderString(){
        return $this->orderString;
    }

    public function orderJson(){
        return $this->orderJson;
    }

    protected function isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
     }    
}