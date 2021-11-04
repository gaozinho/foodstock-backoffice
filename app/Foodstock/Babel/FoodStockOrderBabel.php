<?php
   
namespace App\Foodstock\Babel;
use App\Foodstock\Babel\OrderBabel;
use App\Foodstock\Babel\ItemBabel;
use App\Foodstock\Babel\PaymentBabel;
use App\Foodstock\Babel\ScheduleBabel;
use App\Foodstock\Babel\PaymentMethodBabel;
use App\Foodstock\Babel\Interfaces\OrderBabelInterface;

use App\Foodstock\Babel\BenefitBabel;
use App\Foodstock\Babel\SponsorshipValueBabel;

class FoodStockOrderBabel extends OrderBabel implements OrderBabelInterface
{
    public function __construct($orderString){
        parent::__construct($orderString);
        $this->brokerName = "FoodStock";
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

    public function payments(){
        $payments = $this->orderJson->payments;
        $paymentMethods = $payments->methods;
        $paymentBabel = new PaymentBabel($payments->pending, $payments->prepaid);
        foreach($paymentMethods as $method){
            $paymentBabel->addMethod(new PaymentMethodBabel(
                    isset($method->wallet) ? $method->wallet->name : null, 
                    $method->method, 
                    $method->prepaid, 
                    $method->currency, 
                    $method->type, 
                    $method->value, 
                    isset($method->cash) ? $method->cash->changeFor : null, 
                    isset($method->card) ? $method->card->brand : null
                )
            );
        }
        return $paymentBabel;
    }

    public function benefits(){

        $benefits = [];
        return $benefits;
    }

    public function brokerId(){
        return $this->orderJson->brokerId;
    } 

    public function brokerName(){
        return $this->brokerName;
    }     
    
    public function schedule(){
        try{
            if(isset($this->orderJson->schedule) && $this->orderJson->schedule){
                return new ScheduleBabel(date("Y-m-d H:i:s", strtotime($this->orderJson->scheduled_at)), date("Y-m-d H:i:s", strtotime($this->orderJson->scheduled_at))); 
            }
        }catch(\Exception $e){
            return false;
        }

        return false;        
    }     

    public function subtotal(){
        return $this->orderJson->subtotal;
    }    

    public function deliveryFee(){
        return $this->orderJson->deliveryFee;
    }    
    
    public function orderAmount(){
        return $this->orderJson->orderAmount;
    }    
    
    public function additionalFees(){
        return $this->orderJson->additionalFees;
    }

    public function benefitsTotal(){
        return $this->orderJson->benefitsTotal;
    }   

    public function shortOrderNumber(){
        return $this->orderJson->shortOrderNumber;
    }

    public function orderType(){
        return "INDOOR";
    }    

    public function createdDate(){
        return date("Y-m-d H:i:s", strtotime($this->orderJson->createdDate));
    }

    public function getFormattedCreatedDate(){
        return date("d/m/Y H:i:s", strtotime($this->getCreatedDate()));
    }    

    public function ordersCountOnMerchant(){
        return 0;
    }

    public function customerName(){
        return $this->orderJson->customerName;
    }

    public function deliveryFormattedAddress(){
       return $this->orderJson->deliveryFormattedAddress;;
    }    
}