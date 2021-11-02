<?php

namespace App\Actions\Product\Ifood;

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
use App\Enums\BrokerType;

use App\Models\FederatedSale;

class BrokerProducts
{

    public function process($productsJson, Restaurant $restaurant){
        $response = [];
        foreach($productsJson->elements as $productJson){
            $response[] = $this->processOne($productJson, $restaurant->id);
        }
        return $response;
    }

    public function processOne($productJson, Restaurant $restaurant){

        
        //Tenta pelo extenal code
        try{
       
            if(!isset($productJson->externalCode) || empty($productJson->externalCode)){ 
                throw new \Exception("Invalid external code");
            }

            $product = Product::where("external_code", $productJson->externalCode)
                ->where("user_id", $restaurant->user_id)
                ->firstOrFail();

            if(!is_object($product)){
                throw new \Exception("External code not found");
            } 

            $product->broker_id = 1;
            //$product->has_parent = $has_parent;
            $product->restaurant_id = $restaurant->id;
            $product->enabled = 1;
            $product->broker_id = BrokerType::Ifood;
            $product->unit_price = (is_object($productJson->price) ? $productJson->price->value : null);
            $product->deleted = 0;
            $product->name = $productJson->name;
            $product->description = $productJson->description ?? null;
            $product->image = (isset($productJson->image) ? $productJson->image : (isset($productJson->imagePath) ? $productJson->imagePath : null));
            $product->json_string = json_encode($productJson);
            $product->save();
            if($productJson->name == "Gengibre"){
                //dd("1", $productJson, $product);
            }     
            return $product;
        }catch(\Exception $e1){
            //Tenta pelo nome
            try{
                
                $product = Product::where("name", $productJson->name)
                    ->where("user_id", $restaurant->user_id)
                    ->firstOrFail();

                //Criar um external code, se não fornecido
                $product->external_code = isset($productJson->externalCode) && $productJson->externalCode != "" ? $productJson->externalCode : (strlen($product->external_code) > 1 ? $product->external_code : $this->generateExternalCode($product->id));
                $product->enabled = 1;
                //$product->has_parent = $has_parent;
                $product->unit_price = (is_object($productJson->price) ? $productJson->price->value : null);
                $product->restaurant_id = $restaurant->id;
                $product->broker_id = BrokerType::Ifood;
                $product->deleted = 0;
                $product->name = $productJson->name;
                $product->description = $productJson->description ?? null;
                $product->image = (isset($productJson->image) ? $productJson->image : (isset($productJson->imagePath) ? $productJson->imagePath : null));
                $product->json_string = json_encode($productJson);                
                $product->save();
                if($productJson->name == "Gengibre"){
                    //dd("2", $productJson);
                }                  
                return $product;
            }catch(\Exception $e2){
               
                //Cria produto
                $product = Product::create([
                    'name' => $productJson->name, 
                    'description' => $productJson->description ?? null, 
                    'image' => (isset($productJson->image) ? $productJson->image : (isset($productJson->imagePath) ? $productJson->imagePath : null)),
                    'json_string' => json_encode($productJson), 
                    'unit_price' => (is_object($productJson->price) ? $productJson->price->value : null),
                    'minimun_stock' => 0, 
                    'current_stock' => 0, 
                    'monitor_stock' => 0, 
                    'unit' => null, 
                    'ean' => null, 
                    'unit_price' => 0, 
                    'index' => null, 
                    'enabled' => 1, 
                    'broker_id' => BrokerType::Ifood, 
                    'deleted' => 0, 
                    'initial_step' => 1,
                    //'parent_id' => null,
                    'user_id' => $restaurant->user_id,
                    'restaurant_id' => $restaurant->id
                ]);
                $product->external_code = isset($productJson->externalCode) && $productJson->externalCode != "" ? $productJson->externalCode : $this->generateExternalCode($product->id);
                $product->save();
                if($productJson->name == "Gengibre"){
                    //dd("3", $productJson);
                }                   
                return $product;
            }
        }
    }

    private function generateExternalCode($id = ""){
        return "Food" . ($id != "" ? str_pad($id, 6, "0", STR_PAD_LEFT) : str_pad(random_int(1, 999999), 6, "0", STR_PAD_LEFT));
    }

}
