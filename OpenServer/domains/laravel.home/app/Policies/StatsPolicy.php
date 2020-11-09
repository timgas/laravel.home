<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;

class StatsPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {

        if (auth()->user()->role == 'admin') {
            return true;
        }
    }
}
