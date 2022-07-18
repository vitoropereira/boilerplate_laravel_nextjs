<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes, Notifiable;



    const COMMON_USER = 1;
    const ADMIN_USER = 2;
    const SUPER_ADMIN = 3;

    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'cell_phone',
        'address1',
        'address2',
        'postcode',
        'neighborhood',
        'city',
        'state',
        'country',
        'approval_status'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_super_admin' => 'integer',
        'access_level' => 'integer',
        'approval_status' => 'integer'
    ];



    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'resource');
    }

    public function customer(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
