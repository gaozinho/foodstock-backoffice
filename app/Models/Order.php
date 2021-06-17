<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $company_id
 * @property integer $restaurant_id
 * @property string $idIfood
 * @property string $reference
 * @property int $shortReference
 * @property string $createdAt
 * @property string $type
 * @property float $subTotal
 * @property float $totalPrice
 * @property float $deliveryFee
 * @property string $deliveryDateTime
 * @property int $preparationTimeInSeconds
 * @property int $localizer
 * @property string $json
 * @property string $rawJson
 * @property Company $company
 * @property Restaurant $restaurant
 * @property Customer[] $customers
 * @property Deliveryaddress[] $deliveryaddresses
 * @property Event[] $events
 * @property Item[] $items
 * @property Merchant[] $merchants
 * @property OrderSummary[] $orderSummaries
 * @property Payment[] $payments
 * @property ProductionMovement[] $productionMovements
 * @property Statusesorder[] $statusesorders
 */
class Order extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['company_id', 'restaurant_id', 'idIfood', 'reference', 'shortReference', 'createdAt', 'type', 'subTotal', 'totalPrice', 'deliveryFee', 'deliveryDateTime', 'preparationTimeInSeconds', 'localizer', 'json', 'rawJson'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
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
    public function customers()
    {
        return $this->hasMany('App\Models\Customer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deliveryaddresses()
    {
        return $this->hasMany('App\Models\Deliveryaddress');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany('App\Models\Event');
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
    public function merchants()
    {
        return $this->hasMany('App\Models\Merchant');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderSummaries()
    {
        return $this->hasMany('App\Models\OrderSummary');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany('App\Models\Payment', 'payment_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productionMovements()
    {
        return $this->hasMany('App\Models\ProductionMovement', 'orders_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statusesorders()
    {
        return $this->hasMany('App\Models\Statusesorder');
    }
}
