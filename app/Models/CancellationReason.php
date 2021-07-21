<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $order_summaries_id
 * @property int $code
 * @property string $reason
 * @property mixed $canceled_json
 * @property string $created_at
 * @property string $updated_at
 * @property OrderSummary $orderSummary
 */
class CancellationReason extends Model
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
    protected $fillable = ['order_summary_id', 'code', 'origin', 'reason', 'canceled_json', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderSummary()
    {
        return $this->belongsTo('App\Models\OrderSummary', 'order_summaries_id');
    }
}
