<?php

namespace App\Actions\ProductionLine;

use App\Models\Restaurant;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class RecoverUserRestaurant
{
    public function recover($user_id){
        $user = auth()->user();

        if($user->hasRole("admin")){
            return Restaurant::where("user_id", "=", $user_id)->where("enabled", 1)->firstOrFail();
        }

        if($user->restaurant_member == 1){
            return $user->restaurants()->where("enabled", 1)->firstOrFail();
        }
    }

    public function recoverAll($user_id){
        $user = auth()->user();

        if($user->hasRole("admin")){
            return Restaurant::where("user_id", "=", $user_id)->where("enabled", 1)->orderBy("created_at", "desc")->get();
        }

        if($user->restaurant_member == 1){
            return $user->restaurants()->where("enabled", 1)->get();
        }
    }    

    public function recoverAllUnauthenticated($user_id){
            return Restaurant::where("user_id", "=", intval($user_id))->where("enabled", 1)->orderBy("created_at", "desc")->get();
    }      

    public function recoverAllIds($user_id){
        $user = auth()->user();

        if($user->hasRole("admin")){
            $values = Restaurant::where("user_id", "=", $user_id)->where("enabled", 1)
                //->selectRaw("GROUP_CONCAT(id SEPARATOR ',') as ids")
                ->select("id")
                ->get();

            return $values;
        }

        if($user->restaurant_member == 1){
            return $user->restaurants()->where("enabled", 1)->select("id")->get()->pluck("id");
        }
    }        

    public function recoverOrNew($user_id){
        return Restaurant::where("user_id", "=", $user_id)->where("enabled", 1)->firstOrNew();
    }
}