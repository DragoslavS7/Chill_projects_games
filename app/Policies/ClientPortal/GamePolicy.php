<?php

namespace App\Policies\ClientPortal;

use App\Game;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamePolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->hasRole([
            User::ROLES['uberAdmin'],
            User::ROLES['admin'],
            User::ROLES['viewer']
        ]);
    }

    public function create(User $user)
    {
        return $user->isUberAdminOrAdmin();
    }

    public function view(User $user){
        return $user->hasRole([
            User::ROLES['uberAdmin'],
            User::ROLES['admin'],
            User::ROLES['viewer']
        ]);
    }

    public function edit(User $user)
    {
        return $user->isUberAdminOrAdmin();
    }

    public function destroy(User $user)
    {
        return $user->isUberAdminOrAdmin();
    }

    public function invite(User $user)
    {
        return $user->hasRole([
            User::ROLES['uberAdmin'],
            User::ROLES['admin'],
            User::ROLES['viewer']
        ]);
    }
}
