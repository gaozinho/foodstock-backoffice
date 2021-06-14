<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $role_id
 * @property integer $production_line_id
 * @property integer $restaurant_id
 * @property integer $production_line_version_id
 * @property string $name
 * @property int $step
 * @property boolean $clickable
 * @property boolean $see_previous
 * @property boolean $next_on_click
 * @property boolean $can_pause
 * @property string $color
 * @property int $version
 * @property boolean $is_active
 * @property string $created_at
 * @property string $updated_at
 * @property ProductionLine $productionLine
 * @property ProductionLineVersion $productionLineVersion
 * @property Restaurant $restaurant
 * @property Role $role
 * @property ProductionMovement[] $productionMovements
 * @property ProductionMovement[] $productionMovements
 */
class ProductionLine extends Model
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
    protected $fillable = ['role_id', 'production_line_id', 'restaurant_id', 'production_line_version_id', 'name', 'step', 'clickable', 'see_previous', 'next_on_click', 'can_pause', 'color', 'version', 'is_active', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productionLine()
    {
        return $this->belongsTo('App\Models\ProductionLine');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productionLineVersion()
    {
        return $this->belongsTo('App\Models\ProductionLineVersion');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     
    public function productionMovements()
    {
        return $this->hasMany('App\Models\ProductionMovement', 'next_step_id');
    }
    */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productionMovements()
    {
        return $this->hasMany('App\Models\ProductionMovement');
    }
}
