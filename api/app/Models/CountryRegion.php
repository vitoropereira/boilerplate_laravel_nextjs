<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryRegion extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'ibge_country_regions_id' => 'integer'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
