<?php

namespace App\Actions\ProductionLine;

use App\Foodstock\Babel\IfoodOrderBabel;
use App\Models\Order;

class GenerateOrderJson
{

    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function generate(){
        if($this->order->broker_id == 1){ //TODO - trocar o 1 pelo ifood
            $orderBabel = new IfoodOrderBabel($this->order->json);
        }
        return $orderBabel->toJson();
    }

    public function generateString(){
        if($this->order->broker_id == 1){
            $orderBabel = new IfoodOrderBabel($this->order->json);
        }
        return $orderBabel->toString();
    }
}