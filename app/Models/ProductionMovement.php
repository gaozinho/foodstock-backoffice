<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $production_line_id
 * @property integer $restaurant_id
 * @property integer $order_summary_id
 * @property int $order_id
 * @property integer $next_step_id
 * @property integer $production_line_version_id
 * @property int $current_step_number
 * @property boolean $step_finished
 * @property string $created_at
 * @property string $updated_at
 * @property string $finished_at
 * @property Order $order
 * @property OrderSummary $orderSummary
 * @property ProductionLine $productionLine
 * @property ProductionLineVersion $productionLineVersion
 * @property Restaurant $restaurant
 * @property User $user
 * @property ProductionLine $productionLine
 */
class ProductionMovement extends Model
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
    protected $fillable = ['user_id', 'production_line_id', 'order_summary_id', 'restaurant_id', 'order_summary_id', 'order_id', 'next_step_id', 'production_line_version_id', 'current_step_number', 'step_finished', 'created_at', 'updated_at', 'finished_at', 'paused', 'paused_at', 'paused_by'];

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
    public function orderSummary()
    {
        return $this->belongsTo('App\Models\OrderSummary');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nextProductionLine()
    {
        return $this->belongsTo('App\Models\ProductionLine', 'next_step_id');
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
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function pausedBy()
    {
        return $this->belongsTo('App\Models\User', "paused_by");
    }    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productionLine()
    {
        return $this->belongsTo('App\Models\ProductionLine');
    }
}
