<?php
   
namespace App\Foodstock\Babel;

use App\Foodstock\Babel\PaymentMethodBabel;

class PaymentBabel
{

    public $pending;
    public $prepaid;
    public $methods;

    public function __construct($pending, $prepaid){
        $this->pending = $pending;
        $this->prepaid = $prepaid;
    }

    public function addMethod(PaymentMethodBabel $method){
        $this->methods[] = $method;
    }

    public function setMethod($methods){
        $this->methods = $methods;
    }    

    public function toArray(){
        return (array) $this;
    }

    public function toJson(){
        return json_decode($this);
    }
}