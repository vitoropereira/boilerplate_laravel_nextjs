<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPrice extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $casts = [
        'available' => 'bool',
        'max_people_quantity' => 'integer',
        'cost_price' => 'integer',
        'sale_price' => 'integer',
    ];

    protected $fillable = [
        'product_id',
        'description',
        'max_people_quantity',
        'cost_price',
        'sale_price',
        'available',
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
