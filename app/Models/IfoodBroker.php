<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $restaurant_id
 * @property integer $broker_id
 * @property string $merchant_id
 * @property string $name
 * @property string $corporateName
 * @property string $validated_at
 * @property boolean $validated
 * @property boolean $enabled
 * @property boolean $acknowledgment
 * @property mixed $merchant_json
 * @property string $userCode
 * @property string $authorizationCode
 * @property string $authorizationCodeVerifier
 * @property string $verificationUrlComplete
 * @property string $usercode_expires
 * @property string $accessToken
 * @property string $refreshToken
 * @property string $expiresIn
 * @property string $created_at
 * @property string $updated_at
 * @property Broker $broker
 * @property Restaurant $restaurant
 */
class IfoodBroker extends Model
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
    protected $fillable = ['restaurant_id', 'broker_id', 'merchant_id', 'name', 'corporateName', 'validated_at', 'validated', 'enabled', 'acknowledgment', 'merchant_json', 'userCode', 'authorizationCode', 'authorizationCodeVerifier', 'verificationUrlComplete', 'usercode_expires', 'accessToken', 'refreshToken', 'expiresIn', 'created_at', 'updated_at'];

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
