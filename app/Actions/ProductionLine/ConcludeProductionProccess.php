<?php

namespace App\Actions\ProductionLine;

use App\Models\OrderSummary;
use App\Models\Order;
use App\Models\ProductionMovement;
use App\Models\ProductionLine;
use App\Models\ProductionLineVersion;
use App\Models\CancellationReason;
use App\Actions\ProductionLine\GenerateOrderJson;
use Illuminate\Support\Facades\DB;

use App\Models\FederatedSale;
use App\Actions\ProductionLine\FinishOrder;

class ConcludeProductionProccess
{
    public function conclude($order_id){
        $order = null;
        try{

            $order = Order::where("order_id", $order_id)->firstOrFail();
            $orderSummary = OrderSummary::where("order_id", $order->id)->where("finalized", 0)->first();

            if(is_object($orderSummary)) (new FinishOrder())->finishOne($order->id);

            return $order_id;
        }catch(\Exception $e){
            DB::rollBack();
            if(env('APP_DEBUG')) throw $e;
            $mensagem = 'Não foi possível cancelar o pedido %d. Mais detalhes: %s';
            throw new \Exception(sprintf($mensagem, $order->id, $e->getMessage()));
        }
    }

}