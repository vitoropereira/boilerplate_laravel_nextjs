<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'birth_date' => 'immutable_date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'resource');
    }

    public function customer_addresses(): HasMany
    {
        return $this->hasMany(CustomerAddresse::class);
    }
}
