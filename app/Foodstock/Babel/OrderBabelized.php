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

    public function payments(){
        if(!isset($this->orderJson->payments)) return null;
        
        $payments = $this->orderJson->payments;
        $paymentMethods = $payments->methods;
        $paymentBabel = new PaymentBabel($payments->pending, $payments->prepaid);
        if(is_array($paymentMethods)){
            foreach($paymentMethods as $method){
                $paymentBabel->addMethod(new PaymentMethodBabel(
                        $method->wallet_name, 
                        $method->method, 
                        $method->prepaid, 
                        $method->currency, 
                        $method->type, 
                        $method->value, 
                        $method->cash_changeFor, 
                        $method->card_brand
                    )
                );
            }
        }
        return $paymentBabel;
    }

    public function benefits(){
        $benefits = [];
        if(isset($this->orderJson->benefits)){
            foreach($this->orderJson->benefits as $benefit){
                $benefitBabel = new BenefitBabel($benefit->targetId ?? null, $benefit->description ?? null, $benefit->value, $benefit->target);
                foreach($benefit->sponsorshipValues as $sponsorshipValue){
                    $benefitBabel->addSponsorshipValues(new SponsorshipValueBabel($sponsorshipValue->description, $sponsorshipValue->name, $sponsorshipValue->value));
                }
                $benefits[] = $benefitBabel;
            }
        }
        return $benefits;
    }  

    public function schedule(){
        if(!isset($this->orderJson->schedule)) return false;
        if($this->orderJson->schedule){
            return new ScheduleBabel(date("Y-m-d H:i:s", strtotime($this->orderJson->schedule->start)), date("Y-m-d H:i:s", strtotime($this->orderJson->schedule->end))); 
        }
        return false;
    }      

    public function brokerId(){
        return $this->orderJson->brokerId ?? null;
    }   
    
    public function brokerName(){
        return $this->orderJson->brokerName ?? null;
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

    public function additionalFees(){
        return $this->orderJson->additionalFees ?? null;
    }

    public function benefitsTotal(){
        return $this->orderJson->benefitsTotal ?? null;
    }     

    public function shortOrderNumber(){
        return substr($this->orderJson->shortOrderNumber ?? null, 0, 4);
    }

    public function createdDate(){
        return isset($this->orderJson->createdDate) ? date("Y-m-d H:i:s", strtotime($this->orderJson->createdDate)) : null;
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

    
    public function orderType(){

        if(isset($this->orderJson->orderType)){
            if($this->orderJson->orderType == "DELIVERY") return "Delivery";
            else if($this->orderJson->orderType == "INDOOR") return "Na mesa";
            elseif($this->orderJson->orderType == "TAKEOUT") return "Balcão";
        }
        
        return $this->orderJson->orderType ?? null;
    }

    public function deliveryFormattedAddress(){
        return $this->orderJson->deliveryFormattedAddress ?? null;
    }     
}