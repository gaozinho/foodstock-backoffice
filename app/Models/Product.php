<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $parent_id
 * @property integer $restaurant_id
 * @property integer $category_id
 * @property string $name
 * @property string $description
 * @property int $minimun_stock
 * @property int $current_stock
 * @property boolean $monitor_stock
 * @property string $external_code
 * @property string $unit
 * @property string $ean
 * @property float $unit_price
 * @property boolean $index
 * @property string $created_at
 * @property string $updated_at
 * @property string $image
 * @property string $serving
 * @property boolean $enabled
 * @property boolean $deleted
 * @property int $initial_step
 * @property Category $category
 * @property Product $product
 * @property Restaurant $restaurant
 * @property OrderHasProduct[] $orderHasProducts
 * @property StockMovement[] $stockMovements
 */
class Product extends Model
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
    protected $fillable = ['parent_id', 'restaurant_id', 'category_id', 'name', 'description', 'minimun_stock', 'current_stock', 'monitor_stock', 'external_code', 'unit', 'ean', 'unit_price', 'index', 'created_at', 'updated_at', 'image', 'serving', 'enabled', 'deleted', 'initial_step'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'parent_id');
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
    public function orderHasProducts()
    {
        return $this->hasMany('App\Models\OrderHasProduct');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockMovements()
    {
        return $this->hasMany('App\Models\StockMovement');
    }
}