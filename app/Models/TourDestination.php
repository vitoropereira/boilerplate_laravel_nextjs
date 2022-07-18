<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourDestination extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'country',
        'country_region',
        'state',
        'city',
        'name',
        'slug',
        'description',
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;


    public function products(): HasMany
    {
        return $this->HasMany(Product::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'resource');
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'featured_image_id');
    }
}
