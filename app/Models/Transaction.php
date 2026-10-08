<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'order_id',
        'invoice_number',
        'total_amount',
        'pay_amount',
        'change_amount',
        'payment_method',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
