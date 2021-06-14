<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $invite_id
 * @property integer $user_id
 * @property string $name
 * @property string $address
 * @property string $complement
 * @property string $cep
 * @property string $cnpj
 * @property string $site
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @property Invite $invite
 * @property User $user
 * @property IfoodBroker[] $ifoodBrokers
 * @property ProductionLine[] $productionLines
 * @property ProductionMovement[] $productionMovements
 * @property RappiBroker[] $rappiBrokers
 */
class Restaurant extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['invite_id', 'user_id', 'name', 'address', 'complement', 'cep', 'cnpj', 'site', 'email', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invite()
    {
        return $this->belongsTo('App\Models\Invite');
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
    public function ifoodBrokers()
    {
        return $this->hasMany('App\Models\IfoodBroker');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productionLines()
    {
        return $this->hasMany('App\Models\ProductionLine');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productionMovements()
    {
        return $this->hasMany('App\Models\ProductionMovement', 'restaurants_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rappiBrokers()
    {
        return $this->hasMany('App\Models\RappiBroker');
    }
}
