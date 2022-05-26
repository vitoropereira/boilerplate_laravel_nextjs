<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'ibge_state_id' => 'integer'
    ];

    public function countryRegion(): BelongsTo
    {
        return $this->belongsTo(CountryRegion::class);
    }
}
