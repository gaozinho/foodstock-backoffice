<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $catalog_id
 * @property integer $restaurant_id
 * @property integer $user_id
 * @property integer $broker_id
 * @property string $name
 * @property string $external_code
 * @property boolean $enabled
 * @property string $created_at
 * @property string $updated_at
 * @property int $sequence
 * @property int $index
 * @property Broker $broker
 * @property Catalog $catalog
 * @property Restaurant $restaurant
 * @property User $user
 * @property Item[] $items
 * @property Product[] $products
 */
class Category extends Model
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
    protected $fillable = ['catalog_id', 'restaurant_id', 'user_id', 'broker_id', 'name', 'external_code', 'enabled', 'created_at', 'updated_at', 'sequence', 'index'];

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
    public function catalog()
    {
        return $this->belongsTo('App\Models\Catalog');
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
}
