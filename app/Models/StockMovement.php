<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $product_id
 * @property integer $restaurant_id
 * @property integer $user_id
 * @property integer $broker_id
 * @property string $name
 * @property float $unit_price
 * @property boolean $movement_type
 * @property int $quantity
 * @property string $created_at
 * @property string $updated_at
 * @property string $unit
 * @property Product $product
 * @property Restaurant $restaurant
 */
class StockMovement extends Model
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
    protected $fillable = ['product_id', 'restaurant_id', 'order_summary_id', 'user_id', 'broker_id', 'name', 'unit_price', 'movement_type', 'quantity', 'created_at', 'updated_at', 'unit'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }
}
