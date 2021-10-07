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

class NeemoOrderBabel extends OrderBabel implements OrderBabelInterface
{
    public function __construct($orderString){
        parent::__construct($orderString);
        $this->brokerName = "neemo";
    }

    public function items(){
        $items = [];
        foreach($this->orderJson->Order->ItemOrder as $item){
            $title = $item->title . (isset($item->name) && $item->name != "" ? " - " . $item->name : "");
            $itemBabel = new ItemBabel($title, $item->quantity, $item->price, $item->total, $item->variacao_ref ?? $item->ref, $item->notes ?? null);
            if(isset($item->ComplementCategories)){
                foreach($item->ComplementCategories as $category){
                    if(isset($category->Complements)){
                        foreach($category->Complements as $option){
                            $itemBabel->addSubitem(new ItemBabel($option->title, $option->quantity, $option->price_un, $option->total, $option->ref ?? null, $option->notes ?? null));
                        }
                    }
                    
                }
            }
            $items[] = $itemBabel;
        }
        return $items;
    }

    public function payments(){
        $payment = $this->orderJson->Order->Payment;
        $paymentBabel = new PaymentBabel("", $payment->method ?? $this->orderJson->Order->payment_method, "", "BRL", "", $this->orderJson->Order->total, $this->orderJson->Order->troco, $payment->method_name ?? $this->orderJson->Order->payment_method);
        return $paymentBabel;
    }

    public function benefits(){

        $benefits = [];
        if(isset($this->orderJson->Order->Vouchers)){
            foreach($this->orderJson->Order->Vouchers as $benefit){
                $benefitBabel = new BenefitBabel($benefit->voucher_brinde_ref ?? null, $benefit->voucher_code ?? null, $benefit->voucher_discount, $benefit->voucher_brinde);
                $benefits[] = $benefitBabel;
            }
        }

        return $benefits;
    }

    public function brokerId(){
        return $this->orderJson->Order->id;
    } 

    public function brokerName(){
        return $this->brokerName;
    }     
    
    public function schedule(){
        return false;
    }     

    public function subtotal(){
        return $this->orderJson->Order->sub_total;
    }    

    public function deliveryFee(){
        return $this->orderJson->Order->tax;
    }    
    
    public function orderAmount(){
        return $this->orderJson->Order->total;
    }    
    
    public function additionalFees(){
        return $this->orderJson->Order->taxa_extra;
    }

    public function benefitsTotal(){
        return $this->orderJson->Order->total_discount;
    }   

    public function shortOrderNumber(){
        return $this->orderJson->Order->order_number;
    }

    public function orderType(){
        return "DELIVERY";
    }    

    public function createdDate(){
        return date("Y-m-d H:i:s", strtotime($this->orderJson->Order->date));
    }

    public function getFormattedCreatedDate(){
        return date("d/m/Y H:i:s", strtotime($this->getCreatedDate()));
    }    

    public function ordersCountOnMerchant(){
        return 0;
    }

    public function customerName(){
        return $this->orderJson->Order->name;
    }

    public function deliveryFormattedAddress(){
        $addressPieces[] = sprintf("%s, %s %s - %s", $this->orderJson->Order->street, 
            $this->orderJson->Order->number, 
            $this->orderJson->Order->complement, 
            $this->orderJson->Order->neighborhood); 
        if(isset($this->orderJson->Order->reference_point)) $addressPieces[] = $this->orderJson->Order->reference_point;
        return implode(" :: ", $addressPieces);
    }    
}