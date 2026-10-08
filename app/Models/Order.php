<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'product_id',
        'product_name',
        'quantity',
        'unit_price',
        'total',
        'customer_name',
        'address',
        'status',
    ];
}