<?php

namespace App\Actions\ProductionLine;

use App\Models\OrderSummary;
use App\Models\Order;
use App\Models\ProductionMovement;
use App\Models\ProductionLine;
use App\Models\ProductionLineVersion;
use App\Actions\ProductionLine\GenerateOrderJson;

class StartProductionProccess
{
    public function start($order_id){
        $order = null;
        try{
            $order = Order::findOrFail($order_id);
            $generateOrderJson = new GenerateOrderJson();
            $orderJson = $generateOrderJson->generate($order->id);

            $orderSummary = OrderSummary::firstOrCreate([
                'order_id' => $order->id, 
                'restaurant_id' => $order->restaurant_id
                ],[
                'order_id' => $order->id,
                'broker_id' => $order->broker_id,
                'friendly_number' => $orderJson->friendly_number,
                'restaurant_id' => $order->restaurant_id,
                'started_at' => date('Y-m-d H:i:s'),
                'order_json' => $generateOrderJson->generateString($order->id)
            ]);

            $productionLine = $this->firstStep($order->restaurant_id);
            $productionLineNextStep = $this->nextStep($order->restaurant_id, $productionLine->step);
            $productionLineVersion = ProductionLineVersion::where("restaurant_id", $order->restaurant_id)->where("is_active", 1)->firstOrFail();
            
            return ProductionMovement::firstOrCreate([
                    'order_id' => $order->id, 
                    'production_line_id' => $productionLine->id
                ],
                [
                    'production_line_id' => $productionLine->id,
                    'current_step_number' => $productionLine->step,
                    'step_finished' => 0,
                    'restaurant_id' => $order->restaurant_id,
                    'order_summary_id' => $orderSummary->id,
                    'order_id' => $order->id,
                    'next_step_id'=> $productionLineNextStep ? $productionLineNextStep->id : null,
                    'production_line_version_id' => $productionLineVersion->id
            ]);
        }catch(\Exception $e){
            if(env('APP_DEBUG')) throw $e;
            $mensagem = 'Não foi possível iniciar o processo do pedido %d. Mais detalhes: %s';
            throw new \Exception(sprintf($mensagem, $order->id, $e->getMessage()));
        }
    }

    protected function firstStep($restaurant_id){
        return ProductionLine::where("restaurant_id", $restaurant_id)->where("is_active", 1)->where("step", 1)->firstOrFail();
    }

    protected function nextStep($restaurant_id, $current_step){
        return ProductionLine::where("restaurant_id", $restaurant_id)->where("is_active", 1)->where("step", ($current_step + 1))->first();
    }
}