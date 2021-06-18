<?php

namespace App\Actions\ProductionLine;

class GenerateOrderJson
{

    public function generate($orderId){
        return json_decode($this->generateString($orderId));
    }

    public function generateString($orderId){
        return '{
            "friendly_number" : ' . $orderId . '
        }';
    }
}