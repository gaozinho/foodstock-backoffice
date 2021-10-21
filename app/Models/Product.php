<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
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
    protected $fillable = ['foodstock_name', 'json_string', 'user_id', 'restaurant_id', 'name', 'description', 'minimun_stock', 'current_stock', 'monitor_stock', 'external_code', 'unit', 'ean', 'unit_price', 'index', 'created_at', 'updated_at', 'image', 'serving', 'enabled', 'deleted', 'initial_step', 'parents'];


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

    public function productionLine()
    {
        $user_id = auth()->user()->user_id ?? auth()->user()->id;
        return \App\Models\ProductionLine::where("user_id", $user_id)
            ->where("step", $this->initial_step)
            ->where("is_active", 1)
            ->first();
    }
    
    public function productionLineName()
    {
        $item = $this->productionLine();
        return is_object($item) ? $item->name : "";
    }    

    public function stockPanels()
    {
        return $this->belongsToMany('App\Models\StockPanel', 'stock_panels_has_products');
    }    
}
