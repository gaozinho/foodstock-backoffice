<?php

namespace App\Actions\ProductionLine;

use App\Models\OrderSummary;
use App\Models\Order;
use App\Models\ProductionMovement;
use App\Models\ProductionLine;
use App\Models\ProductionLineVersion;
use App\Actions\ProductionLine\GenerateOrderJson;

use App\Models\FederatedSale;

class CancelProductionProccess
{
    public function cancel($order_id, $broker_id){
        $order = null;
        try{

            $order = Order::where("order_id", $order_id)->firstOrFail();
            $orderSummary = OrderSummary::where("order_id", $order->id)
                ->where("broker_id", $broker_id)
                ->firstOrFail();
            $orderSummary->canceled = 1;
            $orderSummary->save();

            //TODO - Tirar valor do relatório
            //try{
                //FederatedSale::create(array_merge(["restaurant_id" => $order->restaurant_id, "broker_id" => $order->broker_id], (array) $orderJson));
            //}catch(\Exception $e){

            //}

            return $order_id;
        }catch(\Exception $e){
            if(env('APP_DEBUG')) throw $e;
            $mensagem = 'Não foi possível cancelar o pedido %d. Mais detalhes: %s';
            throw new \Exception(sprintf($mensagem, $order->id, $e->getMessage()));
        }
    }

}