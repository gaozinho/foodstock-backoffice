<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $restaurant_id
 * @property int $version
 * @property boolean $is_active
 * @property string $created_at
 * @property string $updated_at
 * @property Restaurant $restaurant
 * @property ProductionLine[] $productionLines
 * @property ProductionMovement[] $productionMovements
 */
class ProductionLineVersion extends Model
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
    protected $fillable = ['restaurant_id', 'user_id', 'version', 'is_active', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productionLines()
    {
        return $this->hasMany('App\Models\ProductionLine');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productionMovements()
    {
        return $this->hasMany('App\Models\ProductionMovement');
    }
}
