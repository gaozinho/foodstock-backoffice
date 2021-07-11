<?php

namespace App\Actions\ProductionLine;

use App\Models\OrderSummary;
use App\Models\Order;
use App\Models\IfoodBroker;
use App\Models\ProductionMovement;
use App\Models\ProductionLine;
use App\Models\ProductionLineVersion;
use App\Actions\ProductionLine\GenerateOrderJson;
use GuzzleHttp\Client;
use App\Foodstock\Babel\OrderBabelized;
use App\Events\FinishedProccess;
use App\Events\ReadyToPickup;
use App\Enums\OrderType;

class ForwardProductionProccess
{
    public function forward($orderNumber, $user_id){
        $order = null;
        try{
            $order = Order::findOrFail($orderNumber);
            $productionMovement = $this->getCurrentMovementByOrderId($order->id);
            $productionMovement->step_finished = 1;
            $productionMovement->finished_at = date("Y-m-d H:i:s");
            $productionMovement->save();

            if(intval($productionMovement->next_step_id) == 0){ //Finaliza processo caso não exista próximo passo
                $this->finishProcess($order->id);
                return false;
            }
            
            $productionLine = ProductionLine::findOrFail($productionMovement->next_step_id);
            $productionLineNextStep = $this->nextStep($order->restaurant_id, $productionLine->step);

            return ProductionMovement::firstOrCreate([
                    'order_id' => $order->id, 
                    'production_line_id' => $productionLine->id
                ],
                [
                    'production_line_id' => $productionLine->id,
                    'current_step_number' => $productionLine->step,
                    'step_finished' => 0,
                    'restaurant_id' => $order->restaurant_id,
                    'order_summary_id' => $productionMovement->order_summary_id,
                    'order_id' => $order->id,
                    'next_step_id'=> $productionLineNextStep ? $productionLineNextStep->id : null,
                    'production_line_version_id' => $productionMovement->production_line_version_id,
                    'user_id' => $user_id
            ]);
        }catch(\Exception $e){
            if(env('APP_DEBUG')) throw $e;
            $mensagem = 'Não foi possível avançar o processo do pedido %d. Mais detalhes: %s';
            throw new \Exception(sprintf($mensagem, $order->id, $e->getMessage()));
        }
    }

    private function getCurrentMovementByOrderId($order_id){
        return ProductionMovement::where("order_id", $order_id)->orderBy('current_step_number', 'desc')->first();
    }

    protected function finishProcess($orderId){

        $orderSummary = OrderSummary::where([
            'order_id' => $orderId
        ])->firstOrFail();

        $babelized = new OrderBabelized($orderSummary->order_json);

        $orderSummary->finalized = 1;
        $orderSummary->finalized_at = date("Y-m-d H:i:s");
        $orderSummary->save();

        FinishedProccess::dispatch(
            $babelized, 
            IfoodBroker::where("restaurant_id", $orderSummary->restaurant_id)->firstOrFail()
        );
    }

    protected function firstStep($restaurant_id){
        return ProductionLine::where("restaurant_id", $restaurant_id)->where("is_active", 1)->where("step", 1)->firstOrFail();
    }

    public function nextStep($restaurant_id, $current_step){
        return ProductionLine::where("restaurant_id", $restaurant_id)->where("is_active", 1)->where("step", ($current_step + 1))->first();
    }
}