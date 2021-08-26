<?php

namespace App\Actions\ProductionLine;

use App\Models\OrderSummary;
use App\Models\Order;
use App\Models\ProductionMovement;
use App\Models\ProductionLine;
use App\Models\ProductionLineVersion;
use App\Models\Product;
use App\Models\Restaurant;
use App\Actions\ProductionLine\GenerateOrderJson;
use App\Actions\Product\ProcessOrderProducts;

use App\Models\FederatedSale;

class StartProductionProccess
{

    public function start($order_id){
        $order = null;
        try{
            $order = Order::findOrFail($order_id);
            $generateOrderJson = new GenerateOrderJson($order);
            $orderJson = $generateOrderJson->generate();

            $orderSummary = OrderSummary::firstOrCreate([
                'order_id' => $order->id, 
                'restaurant_id' => $order->restaurant_id
                ],[
                'order_id' => $order->id,
                'broker_id' => $order->broker_id,
                'friendly_number' => $orderJson->shortOrderNumber,
                'restaurant_id' => $order->restaurant_id,
                'started_at' => date('Y-m-d H:i:s'),
                'order_json' => $generateOrderJson->generateString()
            ]);

            $initialProductionMovement = $this->createFirstMovement($order, $orderSummary);
            
            try{ // Criar/tratar produtos e estoque
                (new ProcessOrderProducts())->process($orderSummary, $generateOrderJson->babelizedOrder());
            }catch(\Exception $e){

            }

            try{ //Relatórios
                FederatedSale::create(array_merge(["restaurant_id" => $order->restaurant_id, "broker_id" => $order->broker_id], (array) $orderJson));
            }catch(\Exception $e){

            }

            $this->placeOrderOnProductionLine($orderSummary, $initialProductionMovement);

            return $initialProductionMovement;
        }catch(\Exception $e){
            if(env('APP_DEBUG')) throw $e;
            $mensagem = 'Não foi possível iniciar o processo do pedido %d. Mais detalhes: %s';
            throw new \Exception(sprintf($mensagem, $order->id, $e->getMessage()));
        }
    }

    protected function createFirstMovement(Order $order, OrderSummary $orderSummary){
        $restaurant = Restaurant::findOrFail($order->restaurant_id);

        $productionLine = $this->firstStep($order->restaurant_id);
        $productionLineNextStep = $this->nextStep($order->restaurant_id, $productionLine->step);
        $productionLineVersion = ProductionLineVersion::where("user_id", $restaurant->user_id)->where("is_active", 1)->firstOrFail();
        
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
    }

    protected function placeOrderOnProductionLine(OrderSummary $orderSummary, ProductionMovement $productionMovement){

        $smallestStep = $this->getSmallestStep($productionMovement->order_summary_id);

        $orderSummary->initial_step = $smallestStep;
        $orderSummary->save();

        if($smallestStep > 1){
            $productionMovement = ProductionMovement::where("order_id", $productionMovement->order_id)->orderBy('current_step_number', 'desc')->first();
            $current_step_number = $productionMovement->current_step_number;

            while($smallestStep > $current_step_number){

                try{
                    $productionLine = ProductionLine::findOrFail($productionMovement->next_step_id);  
                    
                    $productionMovement->step_finished = 1;
                    $productionMovement->finished_at = date("Y-m-d H:i:s");
                    $productionMovement->save();                

                    $productionLineNextStep = $this->nextStep($productionMovement->restaurant_id, $productionLine->step);
                    
                    $productionMovement = ProductionMovement::firstOrCreate([
                            'order_id' => $productionMovement->order_id, 
                            'production_line_id' => $productionLine->id
                        ],
                        [
                            'production_line_id' => $productionLine->id,
                            'current_step_number' => $productionLine->step,
                            'step_finished' => 0,
                            'restaurant_id' => $productionMovement->restaurant_id,
                            'order_summary_id' => $productionMovement->order_summary_id,
                            'order_id' => $productionMovement->order_id,
                            'next_step_id'=> $productionLineNextStep ? $productionLineNextStep->id : null,
                            'production_line_version_id' => $productionMovement->production_line_version_id
                    ]);
                    $current_step_number = $productionMovement->current_step_number;
                }catch(\Exception $e){
                    $current_step_number = 999;
                }
            }
        }
    }

    protected function getSmallestStep($order_summary_id){
        return Product::join("order_has_products", "products.id", "=", "order_has_products.product_id")
        ->where("order_has_products.order_summary_id", $order_summary_id)
        ->where("initial_step", ">", 0)
        ->min("products.initial_step");
    }

    protected function firstStep($restaurant_id){ 
        $restaurant = Restaurant::findOrFail($restaurant_id);
        return ProductionLine::where("user_id", $restaurant->user_id)
        ->where("is_active", 1)
        ->where("step", 1)
        ->firstOrFail();
    }

    protected function nextStep($restaurant_id, $current_step){
        $restaurant = Restaurant::findOrFail($restaurant_id);
        return ProductionLine::where("user_id", $restaurant->user_id)->where("is_active", 1)->where("step", ($current_step + 1))->first();
    }
}