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

class IfoodOrderBabel extends OrderBabel implements OrderBabelInterface
{
    public function __construct($orderString){
        parent::__construct($orderString);
        $this->brokerName = "ifood";
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
        if(isset($this->orderJson->benefits)){
            foreach($this->orderJson->benefits as $benefit){
                $benefitBabel = new BenefitBabel($benefit->targetId ?? null, $benefit->description ?? null, $benefit->value, $benefit->target);
                foreach($benefit->sponsorshipValues as $sponsorshipValue){
                    $benefitBabel->addSponsorshipValues(new SponsorshipValueBabel($sponsorshipValue->description ?? null, $sponsorshipValue->name, $sponsorshipValue->value));
                }
                $benefits[] = $benefitBabel;
            }
        }

        return $benefits;
    }

    public function brokerId(){
        return $this->orderJson->id;
    } 

    public function brokerName(){
        return $this->brokerName;
    }     
    
    public function schedule(){
        if($this->orderJson->orderTiming == "SCHEDULED"){
            return new ScheduleBabel(date("Y-m-d H:i:s", strtotime($this->orderJson->schedule->deliveryDateTimeStart)), date("Y-m-d H:i:s", strtotime($this->orderJson->schedule->deliveryDateTimeEnd))); 
        }
        return false;
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
    
    public function additionalFees(){
        return $this->orderJson->total->additionalFees;
    }

    public function benefitsTotal(){
        return $this->orderJson->total->benefits;
    }   

    public function shortOrderNumber(){
        return $this->orderJson->displayId;
    }

    public function orderType(){

        if(isset($this->orderJson->indoor) && isset($this->orderJson->indoor->table)){
            return $this->orderJson->orderType . " (MESA: " . $this->orderJson->indoor->table . ")";
        }

        return $this->orderJson->orderType;
    }    

    public function createdDate(){
        return date("Y-m-d H:i:s", strtotime($this->orderJson->createdAt));
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
        if(!isset($this->orderJson->delivery)) return "N/A";
        $addressPieces[] = $this->orderJson->delivery->deliveryAddress->formattedAddress;
        if(isset($this->orderJson->delivery->deliveryAddress->reference)) $addressPieces[] = $this->orderJson->delivery->deliveryAddress->reference;
        return implode(" :: ", $addressPieces);
    }    
}