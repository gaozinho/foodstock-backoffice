<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $lgpd_term_id
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property LgpdTerm $lgpdTerm
 */
class LgpdUserAcceptance extends Model
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
    protected $fillable = ['lgpd_term_id', 'user_id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lgpdTerm()
    {
        return $this->belongsTo('App\Models\LgpdTerm');
    }
}
