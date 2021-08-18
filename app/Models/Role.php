<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $guard_name
 * @property string $created_at
 * @property string $updated_at
 * @property ModelHasRole[] $modelHasRoles
 * @property ProductionLine[] $productionLines
 * @property Permission[] $permissions
 */
class Role extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'guard_name', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modelHasRoles()
    {
        return $this->hasMany('App\Models\ModelHasRole');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productionLines()
    {
        return $this->hasMany('App\Models\ProductionLine');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission', 'role_has_permissions');
    }

    public function productionLineNameByRole(){
        $restaurant = (new \App\Actions\ProductionLine\RecoverUserRestaurant())->recover(auth()->user()->id);
        return \App\Models\ProductionLine::where("restaurant_id", $restaurant->id)
        ->where("step", str_replace("step", "", $this->name))
        ->where("is_active", 1)
        ->first();
    }
}
