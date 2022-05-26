<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAddresse extends Model
{
    use HasFactory, SoftDeletes;

    const BILLING_AND_DELIVERY_ADDRESS = 1;
    const BILLING_ADDRESS = 2;
    const DELIVERY_ADDRESS = 3;



    protected $casts = [
        'type' => 'integer'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
