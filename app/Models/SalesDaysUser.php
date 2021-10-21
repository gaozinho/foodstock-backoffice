<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesDaysUser extends Model
{

    protected $fillable = ['user_id', 'external_code', 'product_id', 'name', 'quantity', 'total', 'date'];

}
