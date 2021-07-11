<?php
   
namespace App\Foodstock\Babel;

class PaymentMethodBabel
{

    public $wallet_name;
    public $method;
    public $prepaid;
    public $currency;
    public $type;
    public $value;
    public $cash_changeFor;
    public $card_brand;

    public function __construct($wallet_name, $method, $prepaid, $currency, $type, $value, $cash_changeFor, $card_brand){
        $this->wallet_name = $wallet_name;
        $this->method = $method;
        $this->prepaid = $prepaid;
        $this->currency = $currency;
        $this->type = $type;
        $this->value = $value;
        $this->cash_changeFor = $cash_changeFor;
        $this->card_brand = $card_brand;
    }

    public function toArray(){
        return (array) $this;
    }

    public function toJson(){
        return json_decode($this);
    }
}