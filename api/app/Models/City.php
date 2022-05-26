<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'ibge_city_id' => 'integer'
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
