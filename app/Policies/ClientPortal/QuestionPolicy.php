<?php

namespace App\Policies\ClientPortal;

use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
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

    public function create($user)
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

    public function edit($user)
    {
        return $user->isUberAdminOrAdmin();
    }

    public function destroy($user)
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
