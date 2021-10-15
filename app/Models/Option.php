<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $option_group_id
 * @property integer $product_id
 * @property int $sequence
 * @property int $index
 * @property string $id_on_broker
 * @property string $created_at
 * @property string $updated_at
 * @property OptionGroup $optionGroup
 * @property Product $product
 */
class Option extends Model
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
    protected $fillable = ['option_group_id', 'product_id', 'sequence', 'index', 'id_on_broker', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function optionGroup()
    {
        return $this->belongsTo('App\Models\OptionGroup');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
