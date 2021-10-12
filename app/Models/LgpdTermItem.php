<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $lgpd_term_id
 * @property string $term
 * @property string $created_at
 * @property string $updated_at
 * @property LgpdTerm $lgpdTerm
 */
class LgpdTermItem extends Model
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
    protected $fillable = ['lgpd_term_id', 'term', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lgpdTerm()
    {
        return $this->belongsTo('App\Models\LgpdTerm');
    }
}
