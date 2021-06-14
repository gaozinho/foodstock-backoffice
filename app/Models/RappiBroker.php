<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $restaurant_id
 * @property integer $broker_id
 * @property string $client_id
 * @property string $client_secret
 * @property string $token
 * @property string $validated_at
 * @property string $created_at
 * @property string $updated_at
 * @property boolean $validated
 * @property boolean $enabled
 * @property boolean $acknowledgment
 * @property Broker $broker
 * @property Restaurant $restaurant
 */
class RappiBroker extends Model
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
    protected $fillable = ['restaurant_id', 'broker_id', 'client_id', 'client_secret', 'token', 'validated_at', 'created_at', 'updated_at', 'validated', 'enabled', 'acknowledgment'];

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
    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }
}
