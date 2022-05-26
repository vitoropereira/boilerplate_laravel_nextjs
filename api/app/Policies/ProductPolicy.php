<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function update(User $user, Product $product)
    {
        return $user->tokenCan('product.update') && $user->access_level >= User::ADMIN_USER;
    }

    public function create(User $user, Product $product)
    {
        return  $user->tokenCan('product.create') && $user->access_level >= User::ADMIN_USER;
    }

    public function delete(User $user, Product $product)
    {
        return  $user->tokenCan('product.delete') && $user->access_level >= User::ADMIN_USER;
    }
}
