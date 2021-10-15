<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $external_code
 * @property string $name
 * @property int $min
 * @property int $max
 * @property int $sequence
 * @property int $index
 * @property string $created_at
 * @property string $updated_at
 * @property Item[] $items
 * @property Option[] $options
 */
class OptionGroup extends Model
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
    protected $fillable = ['broker_id', 'restaurant_id', 'external_code', 'name', 'min', 'max', 'sequence', 'index', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function items()
    {
        return $this->belongsToMany('App\Models\Item', 'items_has_option_groups');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany('App\Models\Option');
    }
}
