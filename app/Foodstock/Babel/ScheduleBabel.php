<?php
   
namespace App\Foodstock\Babel;

use App\Foodstock\Babel\PaymentMethodBabel;

class ScheduleBabel
{

    public $start;
    public $end;

    public function __construct($start, $end){
        $this->start = $start;
        $this->end = $end;
    }
    
    public function toArray(){
        return (array) $this;
    }

    public function toJson(){
        return json_decode($this);
    }
}