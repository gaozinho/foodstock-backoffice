<?php

namespace App\Actions\ProductionLine;

use App\Foodstock\Babel\IfoodOrderBabel;
use App\Foodstock\Babel\NeemoOrderBabel;
use App\Models\Order;
use App\Enums\BrokerType;

class GenerateOrderJson
{

    private $order;
    private $orderBabel;

    public function __construct(Order $order)
    {
        $this->order = $order;
        if($this->order->broker_id == BrokerType::Ifood){
            $this->orderBabel = new IfoodOrderBabel($this->order->json);
        }else if($this->order->broker_id == BrokerType::Neemo){
            $this->orderBabel = new NeemoOrderBabel($this->order->json);
        }else{
            throw new \Exception("Invalid broker type.");
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