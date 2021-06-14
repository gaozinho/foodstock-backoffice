<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $logo
 * @property string $endpoint
 * @property string $authenticationApi
 * @property string $merchantApi
 * @property string $usercodeApi
 * @property boolean $enabled
 * @property string $guidelines
 * @property string $access_token
 * @property string $expires
 * @property string $client_centralized_id
 * @property string $client_centralized_secret
 * @property string $client_distributed_id
 * @property string $client_distributed_secret
 * @property string $created_at
 * @property string $updated_at
 * @property IfoodBroker[] $ifoodBrokers
 * @property RappiBroker[] $rappiBrokers
 */
class Broker extends Model
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
    protected $fillable = ['name', 'logo', 'endpoint', 'authenticationApi', 'merchantApi', 'usercodeApi', 'enabled', 'guidelines', 'access_token', 'expires', 'client_centralized_id', 'client_centralized_secret', 'client_distributed_id', 'client_distributed_secret', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ifoodBrokers()
    {
        return $this->hasMany('App\Models\IfoodBroker');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rappiBrokers()
    {
        return $this->hasMany('App\Models\RappiBroker');
    }
}
