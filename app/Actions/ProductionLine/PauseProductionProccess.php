<?php

namespace App\Actions\ProductionLine;

use App\Models\OrderSummary;
use App\Models\Order;
use App\Models\ProductionMovement;
use App\Models\ProductionLine;
use App\Models\ProductionLineVersion;
use App\Actions\ProductionLine\GenerateOrderJson;

class PauseProductionProccess
{
    public function pause($order_id, $user_id){
        $order = null;
        try{
            $productionMovement = ProductionMovement::where("order_id", $order_id)->orderBy('current_step_number', 'desc')->first();
            $productionMovement->paused = 1;
            $productionMovement->paused_by = $user_id;
            $productionMovement->paused_at = date("Y-m-d H:i:s");
            $productionMovement->save();
        }catch(\Exception $e){
            if(env('APP_DEBUG')) throw $e;
            $mensagem = 'Não foi possível pausar o processo do pedido %d. Mais detalhes: %s';
            throw new \Exception(sprintf($mensagem, $order->id, $e->getMessage()));
        }
    }
}