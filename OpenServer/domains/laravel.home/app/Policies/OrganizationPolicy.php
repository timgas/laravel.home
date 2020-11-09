<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


class OrganizationPolicy
{
    use HandlesAuthorization;


    /**
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->role == 'admin') {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->role == 'employer') {
            return true;
        } else {
            return  abort('403', 'Action not allowed: AuthorizationException');
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Organization $organization
     * @return mixed
     */
    public function view(User $user, Organization $organization)
    {
       if (Auth::user()->id == $organization->user_id) {
           return true;
       } else {
           return  abort('403', 'Action not allowed: AuthorizationException');
       }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->role == 'employer' ) {
            return true;
        } else {
            return  abort('403', 'Action not allowed: AuthorizationException');
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Organization $organization
     * @return mixed
     */
    public function update(User $user, Organization $organization)
    {
        if ($user->id == $organization->user_id) {
            return true;
        } else {
            return $this->deny('You do not have permission to update', '403');
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Organization $organization
     * @return mixed
     */
    public function delete(User $user, Organization $organization)
    {
        if (Auth::user()->id == $organization->user_id) {
            return true;
        } else {
            return $this->deny('You do not have permission to delete', '403');
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Organization $organization
     * @return mixed
     */
    public function restore(User $user, Organization $organization)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Organization $organization
     * @return mixed
     */
    public function forceDelete(User $user, Organization $organization)
    {
        //
    }
}
