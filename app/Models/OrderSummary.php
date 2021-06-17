<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $restaurant_id
 * @property int $order_id
 * @property int $friendly_number
 * @property mixed $order_json
 * @property boolean $finalized
 * @property string $created_at
 * @property string $updated_at
 * @property string $started_at
 * @property string $finalized_at
 * @property Order $order
 * @property Restaurant $restaurant
 * @property ProductionMovement[] $productionMovements
 */
class OrderSummary extends Model
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
    protected $fillable = ['restaurant_id', 'order_id', 'friendly_number', 'order_json', 'finalized', 'created_at', 'updated_at', 'started_at', 'finalized_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

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
    public function productionMovements()
    {
        return $this->hasMany('App\Models\ProductionMovement');
    }
}
