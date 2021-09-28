<?php

namespace App\Actions\Help;

use App\Models\IfoodBroker;
use App\Models\RappiBroker;
use App\Models\ProductionLine;
use App\Actions\ProductionLine\RecoverUserRestaurant;
use App\Integrations\IfoodIntegrationDistributed;
use Illuminate\Support\Facades\Log;

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

        $restaurants = (new RecoverUserRestaurant())->recoverAll(auth()->user()->id);

        foreach($restaurants as $restaurant){
            $this->brokersOk[$restaurant->name] = [
                "IFOOD" => IfoodBroker::where("restaurant_id", $restaurant->id)->where("validated", 1)->count() > 0,
                //"RAPPI" => RappiBroker::where("restaurant_id", $this->restaurant->id)->where("validated", 1)->count() > 0
            ];
        }

        foreach($this->brokersOk as $restaurantName => $broker){
            foreach($broker as $success){
                if(!$success) return false;
            }
        }

        return true;
    }

    public function proccessOk(){
        return ProductionLine::where("user_id", auth()->user()->id)->count() > 0;
    }

    public function merchantsAvailable(){
        $merchantsInfo = [];
        try{
            $ifoodIntegrationDistributed = new IfoodIntegrationDistributed(); 

            $restaurants = (new RecoverUserRestaurant())->recoverAll(auth()->user()->id);

            foreach($restaurants as $restaurant){
                $availableData = $ifoodIntegrationDistributed->merchantAvailable($restaurant->id);

                //$availableReason = $availableData->message->title . " - " . $availableData->message->subtitle;

                if(is_object($availableData)){
                    $merchantsInfo["IFOOD"][$restaurant->name] = [
                        "available" => ($availableData->status == "AVAILABLE"), 
                        "reason" => ($availableData->status == "AVAILABLE") ? "Loja aberta" : "Loja fechada" // $availableData->message->title . (isset($availableData->message->subtitle) && !empty($availableData->message->subtitle) ? " - " . $availableData->message->subtitle : "")
                    ];
                }else if(is_array($availableData)){
                    $info = [];
                    foreach($availableData as $data){
                        $info[] = $data->message->title . " para " . $data->operation;
                    }
                    $merchantsInfo["IFOOD"][$restaurant->name] = [
                        "available" => ($data->available == "AVAILABLE"), 
                        "reason" => implode(", ", $info) 
                    ];
                }
            }

        }catch(\Exception $e){
            dd($e);
            Log::error("Error on health check", [
                "message" => $e->getMessage()
            ]);
            $merchantsInfo[""][""] = [
                "available" => false, 
                "reason" => "Nenhuma integração configurada"
            ];
        }

        return $merchantsInfo;
    }    
}