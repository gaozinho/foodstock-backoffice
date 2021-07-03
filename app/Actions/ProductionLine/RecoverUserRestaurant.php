<?php

namespace App\Actions\ProductionLine;

use App\Models\Restaurant;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class RecoverUserRestaurant
{
    public function recover($user_id){
        $user = auth()->user();

        if($user->hasRole("admin")){
            return Restaurant::where("user_id", "=", $user_id)->firstOrFail();
        }

        if($user->restaurant_member == 1){
            return $user->restaurants()->firstOrFail();
        }
    }

    public function recoverOrNew($user_id){
        return Restaurant::where("user_id", "=", $user_id)->firstOrNew();
    }
}