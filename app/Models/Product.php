<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $casts = [
        'available' => 'bool',
    ];

    protected $fillable = [
        'tour_destination_id',
        'name',
        'slug',
        'description',
        'available',
        'meta_title',
        'meta_description'
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function tourDestination(): BelongsTo
    {
        return $this->belongsTo(TourDestination::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'resource');
    }

    public function productPrices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'featured_image_id');
    }
}
