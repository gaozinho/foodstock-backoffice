<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property float $restaurant_id
 * @property string $date
 * @property float $amount
 */
class FederatedDaySale extends Model
{
    public $timestamps = false;
    
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['restaurant_id', 'date', 'amount'];

}
