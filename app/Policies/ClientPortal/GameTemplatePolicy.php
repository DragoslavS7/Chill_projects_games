<?php

namespace App\Policies\ClientPortal;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameTemplatePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */

    public function select(User $user)
    {
        return $user->isUberAdminOrAdmin();
    }

}
