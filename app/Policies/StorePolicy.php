<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StorePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Store $store): bool
    {
        return $user->id === $store->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Store $store): Response
    {
        if ($user->role === 'admin') {
            return Response::allow();
        }

        if ($user->id === $store->user_id) {
            return Response::allow();
        }

        return Response::deny("You Don't Have Permission To Update This Store");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Store $store): Response
    {
        return $user->id === $store->user_id || $user->role === 'admin' ? Response::allow() : Response::deny("You Don't Have Permission To Delete This Store");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Store $store): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Store $store): bool
    {
        return false;
    }
}
