<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $order_summary_id
 * @property integer $product_id
 * @property integer $broker_id
 * @property int $quantity
 * @property float $unity_price
 * @property string $created_at
 * @property string $updated_at
 * @property Broker $broker
 * @property OrderSummary $orderSummary
 * @property Product $product
 */
class OrderHasProduct extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['order_summary_id', 'product_id', 'broker_id', 'quantity', 'unity_price', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function broker()
    {
        return $this->belongsTo('App\Models\Broker');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderSummary()
    {
        return $this->belongsTo('App\Models\OrderSummary');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
