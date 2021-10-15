<?php

namespace App\Actions\Product;

use App\Models\OrderSummary;
use App\Models\Order;
use App\Models\ProductionMovement;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\OrderHasProduct;
use App\Models\StockMovement;
use App\Models\ProductionLine;
use App\Models\ProductionLineVersion;
use App\Actions\ProductionLine\GenerateOrderJson;
use App\Foodstock\Babel\OrderBabel;
use Illuminate\Support\Facades\DB;

use App\Models\FederatedSale;

class ProcessOrderProducts
{
    public function process(OrderSummary $orderSummary, OrderBabel $order){
        foreach($order->items as $item){
            try{
                DB::beginTransaction();
                $this->processItems($orderSummary, $item);
                DB::commit();
            }catch(\Exception $e){
                DB::rollBack();
                if(env('APP_DEBUG')) throw $e;
            }            
        }
    }

    public function getRestaurantsByRestaurant($restaurant_id){
        $items = DB::select(DB::raw("SELECT r1.id FROM restaurants r
            INNER JOIN restaurants r1 ON r1.user_id = r.user_id
            WHERE r.id = " . $restaurant_id . " AND r1.enabled = 1"
        ));

        $final = array();

        array_walk_recursive($items, function($item, $key) use (&$final){
            $final[] = $item->id;
        });

        return $final;
    }

    private function processItems(OrderSummary $orderSummary, $item){

        $product = null;
        //$restaurants = array_values($this->getRestaurantsByRestaurant($orderSummary->restaurant_id));

        $restaurant = Restaurant::find($orderSummary->restaurant_id);

        try{
            //Encontrar produto pelo external code
            if(empty($item->externalCode)) throw new \Exception("Invalid external code");

            $product = Product::where("external_code", $item->externalCode)
                ->where("user_id", $restaurant->user_id)
                ->firstOrFail();

            if(!is_object($product)) throw new \Exception("External code not found");

            if($product->deleted == 1){
                $product->current_stock = 0 - intval($item->quantity);
            }else{
                $product->current_stock = $product->current_stock - intval($item->quantity);
            }

            $product->enabled = 1;
            $product->deleted = 0;            
            $product->save();
        }catch(\Exception $e1){
            try{
                
                //Encontrar pelo nome
                $product = Product::where("name", $item->name)
                    ->where("user_id", $restaurant->user_id)
                    ->firstOrFail();

                if($product->deleted == 1){
                    $product->current_stock = 0 - intval($item->quantity);
                }else{
                    $product->current_stock = $product->current_stock - intval($item->quantity);
                }

                //Criar um external code, se não fornecido
                $product->external_code = isset($item->externalCode) && $item->externalCode != "" ? $item->externalCode : $this->generateExternalCode($product->id);
                
                $product->enabled = 1;
                $product->deleted = 0;
                $product->save();
            }catch(\Exception $e2){
                //Cria produto
                $product = Product::create([
                    //'restaurant_id' => null, //$orderSummary->restaurant_id, 
                    'name' => $item->name, 
                    'description' => null, 
                    'minimun_stock' => 0, 
                    'current_stock' => 0 - intval($item->quantity), 
                    'monitor_stock' => 0, 
                    'unit' => null, 
                    'ean' => null, 
                    'unit_price' => $item->unitPrice, 
                    'index' => null, 
                    'enabled' => 1, 
                    'deleted' => 0, 
                    'initial_step' => 1,
                    //'parent_id' => $parent_id,
                    'user_id' => $restaurant->user_id
                ]);
                $product->external_code = isset($item->externalCode) && $item->externalCode != "" ? $item->externalCode : $this->generateExternalCode($product->id);
                $product->save();
            }
        }

        //Vincula ao pedido
        OrderHasProduct::updateOrCreate(
            [
                'order_summary_id' => $orderSummary->id, 
                'product_id' => $product->id
            ],
            [
                'order_summary_id' => $orderSummary->id, 
                'product_id' => $product->id, 
                'broker_id' => $orderSummary->broker_id, 
                'quantity' => $item->quantity, 
                'unity_price' => $item->unitPrice, 
        ]);

        //Movimenta estoque
        StockMovement::updateOrCreate([
            'order_summary_id' => $orderSummary->id, 
            'product_id' => $product->id
            ],[
            'product_id' => $product->id, 
            //'restaurant_id' => $orderSummary->restaurant_id, 
            'order_summary_id' => $orderSummary->id, 
            'user_id' => null, 
            'broker_id' => $orderSummary->broker_id, 
            'name' => $product->name, 
            'unit_price' => $item->unitPrice, 
            'movement_type' => 0, 
            'quantity' => $item->quantity, 
            'unit' => null
        ]);

        if(isset($item->subitems) && is_array($item->subitems)){
            foreach($item->subitems as $subitem){
                $this->processItems($orderSummary, $subitem, $product->id);
            }
        }

        return $product;
    }

    private function generateExternalCode($id = ""){
        return "FDSK" . ($id != "" ? str_pad($id, 6, "0", STR_PAD_LEFT) : str_pad(random_int(1, 999999), 6, "0", STR_PAD_LEFT));
    }

}
