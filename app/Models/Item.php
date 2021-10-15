<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $product_id
 * @property integer $catalog_id
 * @property integer $category_id
 * @property int $serving
 * @property string $external_code
 * @property string $created_at
 * @property string $updated_at
 * @property Catalog $catalog
 * @property Category $category
 * @property Product $product
 * @property DietaryRestriction[] $dietaryRestrictions
 * @property OptionGroup[] $optionGroups
 */
class Item extends Model
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
    protected $fillable = ['product_id', 'catalog_id', 'category_id', 'serving', 'external_code', 'created_at', 'updated_at'];

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
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dietaryRestrictions()
    {
        return $this->hasMany('App\Models\DietaryRestriction');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function optionGroups()
    {
        return $this->belongsToMany('App\Models\OptionGroup', 'items_has_option_groups');
    }
}
