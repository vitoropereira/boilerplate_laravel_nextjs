<?php

namespace App\Policies;

use App\Models\TourDestination;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TourDestinationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function update(User $user, TourDestination $tour_destination)
    {
        return $user->tokenCan('tour_destination.update') && $user->access_level >= User::ADMIN_USER;
    }

    public function create(User $user, TourDestination $tour_destination)
    {
        return  $user->tokenCan('tour_destination.create') && $user->access_level >= User::ADMIN_USER;
    }

    public function delete(User $user, TourDestination $tour_destination)
    {
        return  $user->tokenCan('tour_destination.delete') && $user->access_level >= User::ADMIN_USER;
    }
}
