<?php

namespace App\Actions\ProductionLine;

use App\Foodstock\Babel\IfoodOrderBabel;
use App\Models\Order;

class GenerateOrderJson
{

    private $order;
    private $orderBabel;

    public function __construct(Order $order)
    {
        $this->order = $order;
        if($this->order->broker_id == 1){ //TODO - trocar o 1 pelo ifood
            $this->orderBabel = new IfoodOrderBabel($this->order->json);
        }        
    }
    public function babelizedOrder(){
        return $this->orderBabel;
    }
    public function generate(){
        return $this->orderBabel->toJson();
    }

    public function generateString(){
        return $this->orderBabel->toString();
    }
}