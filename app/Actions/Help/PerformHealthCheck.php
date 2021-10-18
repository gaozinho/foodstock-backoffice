<?php

namespace App\Actions\Help;

use App\Models\IfoodBroker;
use App\Models\NeemoBroker;
use App\Models\RappiBroker;
use App\Models\ProductionLine;
use App\Actions\ProductionLine\RecoverUserRestaurant;
use App\Integrations\IfoodIntegrationDistributed;
use Illuminate\Support\Facades\Log;
use App\Integrations\NeemoIntegration;

class PerformHealthCheck
{

    private $user;
    private $menagesRestaurants;
    private $restaurant;
    public $brokersOk = [];
    public $merchantsOk = [];
    public $availableData, $availableReason;

    public function __construct()
    {
        $this->user = auth()->user();
        //(new RecoverUserRestaurant())->recoverAllIds($this->user->id);
//dd((new RecoverUserRestaurant())->recoverAllIds($this->user->id));

        //$this->restaurant =  (new RecoverUserRestaurant())->recoverOrNew($this->user->id);
    }

    public function deliveryOk(){
        return $this->user->menagesRestaurants();
    }

    public function integrationOk(){
        if(!$this->deliveryOk()) return false;


        return true;
    }

    public function proccessOk(){
        return ProductionLine::where("user_id", auth()->user()->id)->count() > 0;
    }

    public function restaurantsConfigureds(){
        $restaurants = (new RecoverUserRestaurant())->recoverAll(auth()->user()->user_id ?? auth()->user()->id);
        $check = [];
        foreach($restaurants as $restaurant){
            $check[$restaurant->name] = ($restaurant->enabled == 1);
        }
        return $check;
    }

    public function merchantsAvailable(){
        $merchantsInfo = [];
        try{
            $ifoodIntegrationDistributed = new IfoodIntegrationDistributed(); 
            $neemoIntegration = new NeemoIntegration();

            $restaurants = (new RecoverUserRestaurant())->recoverAll(auth()->user()->id);

            foreach($restaurants as $restaurant){
                $availableData = $ifoodIntegrationDistributed->merchantAvailable($restaurant->id);
            
                //$availableReason = $availableData->message->title . " - " . $availableData->message->subtitle;

                if(is_object($availableData)){
                    $merchantsInfo["IFOOD"][] = [
                        "validated" => IfoodBroker::where("restaurant_id", $restaurant->id)->where("validated", 1)->count() > 0,
                        "restaurant" => $restaurant->name, 
                        "available" => ($availableData->status == "AVAILABLE"), 
                        "reason" => ($availableData->status == "AVAILABLE") ? "Loja aberta" : "Loja fechada" // $availableData->message->title . (isset($availableData->message->subtitle) && !empty($availableData->message->subtitle) ? " - " . $availableData->message->subtitle : "")
                    ];
                }else if(is_array($availableData)){
                    $info = [];
                    foreach($availableData as $data){
                        $info[] = $data->message->title . " para " . $data->operation;
                    }
                    $merchantsInfo["IFOOD"][] = [
                        "validated" => IfoodBroker::where("restaurant_id", $restaurant->id)->where("validated", 1)->count() > 0,
                        "restaurant" => $restaurant->name, 
                        "available" => ($data->available == "AVAILABLE"), 
                        "reason" => implode(", ", $info) 
                    ];
                }

                //NEEMO
                $neemoInfo = $neemoIntegration->validateRestaurant($restaurant->id);
                $merchantsInfo["NEEMO"][] = [
                    "validated" => NeemoBroker::where("restaurant_id", $restaurant->id)->where("validated", 1)->count() > 0,
                    "restaurant" => $restaurant->name, 
                    "available" => $neemoInfo["success"], 
                    "reason" => $neemoInfo["message"]
                ];                
            }

        }catch(\Exception $e){
            Log::error("Error on health check", [
                "message" => $e->getMessage()
            ]);
        }

        return $merchantsInfo;
    }    
}