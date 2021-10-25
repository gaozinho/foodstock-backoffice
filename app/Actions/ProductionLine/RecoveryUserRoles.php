<?php

namespace App\Actions\ProductionLine;

use App\Models\Restaurant;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Actions\ProductionLine\RecoverUserRestaurant;

class RecoveryUserRoles
{
    public function roles(){
        $user = Auth::user();
        
        if($user->hasRole('admin')){
            return Role::join("production_lines", "production_lines.role_id", "=", "roles.id")
                ->where("production_lines.is_active", 1)
                ->where("production_lines.production_line_id", null)
                ->where("production_lines.user_id", $user->id)
                ->where("roles.guard_name", "production-line")
                ->select(["roles.*", 'production_lines.name as custom_name'])
                ->orderBy("production_lines.step")
                ->get();
        }else{
            $roles = $user->roles()->select("roles.id")->get()->pluck("id")->toArray(); 
            return Role::join("production_lines", "production_lines.role_id", "=", "roles.id")
                ->where("production_lines.is_active", 1)
                ->where("production_lines.production_line_id", null)
                ->where("production_lines.user_id", $user->user_id)
                //->where("roles.guard_name", "production-line")
                ->whereIn("roles.id", $roles)
                ->select(["roles.*", 'production_lines.name as custom_name'])
                ->orderBy("production_lines.step")
                ->get();
                    
        }
    }
}