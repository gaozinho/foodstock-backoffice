<?php

namespace App\Actions\Help;

use App\Models\IfoodBroker;
use App\Models\RappiBroker;
use App\Models\ProductionLine;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class PerformHealthCheck
{

    private $user;
    private $menagesRestaurants;
    private $restaurant;
    public $brokersOk = [];

    public function __construct()
    {
        $this->user = auth()->user();
        $this->restaurant =  (new RecoverUserRestaurant())->recoverOrNew($this->user->id);
    }

    public function deliveryOk(){
        return $this->user->menagesRestaurants();
    }

    public function integrationOk(){
        if(!$this->deliveryOk()) return false;

        $this->brokersOk = [
            "IFOOD" => IfoodBroker::where("restaurant_id", $this->restaurant->id)->where("validated", 1)->count() > 0,
            "RAPPI" => RappiBroker::where("restaurant_id", $this->restaurant->id)->where("validated", 1)->count() > 0
        ];

        $success = false;

        foreach($this->brokersOk as $ok){
            if($ok) return true;
        }
    }

    public function proccessOk(){
        return ProductionLine::where("restaurant_id", $this->restaurant->id)->count() > 0;
    }
}