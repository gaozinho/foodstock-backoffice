<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $version
 * @property string $term
 * @property string $publishing_date
 * @property string $created_at
 * @property string $updated_at
 * @property LgpdTermItem[] $lgpdTermItems
 * @property LgpdUserAcceptance[] $lgpdUserAcceptances
 */
class LgpdTerm extends Model
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
    protected $fillable = ['version', 'term', 'publishing_date', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lgpdTermItems()
    {
        return $this->hasMany('App\Models\LgpdTermItem');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lgpdUserAcceptances()
    {
        return $this->hasMany('App\Models\LgpdUserAcceptance');
    }
}
