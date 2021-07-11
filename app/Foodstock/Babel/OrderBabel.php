<?php
   
namespace App\Foodstock\Babel;
use App\Foodstock\Babel\ItemBabel;
use App\Foodstock\Babel\Interfaces\OrderBabelInterface;

class OrderBabel
{
    protected $orderString = null;
    protected $orderJson = null;

    public $items, 
        $subtotal, 
        $deliveryFee, 
        $orderAmount, 
        $shortOrderNumber,
        $createdDate, 
        $ordersCountOnMerchant, 
        $customerName, 
        $deliveryFormattedAddress, 
        $brokerId,
        $orderType,
        $benefits,
        $additionalFees,
        $benefitsTotal;

    public $payments, $schedule;

    public function __construct($orderString){
        if($this->isJson($orderString)){
            $this->orderString = $orderString;
            $this->orderJson = json_decode($orderString);

            $this->brokerId = $this->brokerId(); 
            $this->items = $this->items(); 
            $this->payments = $this->payments(); 
            $this->subtotal = $this->subtotal(); 
            $this->deliveryFee = $this->deliveryFee(); 
            $this->orderAmount = $this->orderAmount(); 
            $this->shortOrderNumber = $this->shortOrderNumber(); 
            $this->createdDate = $this->createdDate(); 
            $this->ordersCountOnMerchant = $this->ordersCountOnMerchant(); 
            $this->customerName = $this->customerName(); 
            $this->deliveryFormattedAddress = $this->deliveryFormattedAddress(); 
            $this->schedule = $this->schedule(); 
            $this->orderType = $this->orderType(); 
            $this->benefits = $this->benefits(); 

            $this->additionalFees = $this->additionalFees();
            $this->benefitsTotal = $this->benefitsTotal();

        }else{
            throw new \Exception("Invalid JSON string.");
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

    public function brokerId(){
        return $this->brokerId;
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