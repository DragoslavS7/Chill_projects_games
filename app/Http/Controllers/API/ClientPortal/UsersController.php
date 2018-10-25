<?php
/**
 * Created by PhpStorm.
 * User: stojan
 * Date: 9/21/2018
 * Time: 11:16 AM
 */

namespace App\Http\Controllers\API\ClientPortal;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;


class UsersController extends Controller
{
    function getUsers($clientPortalId){
        $clientPortal = \App\ClientPortal::find($clientPortalId);

        $error = [];

        $numUsers = 0;

        if(!$clientPortal){
            $error['error'] = "Client portal with id `$clientPortalId` does not exists.";
            return response($error, 404);
        }

        $user = new User();
        $numUsers = $user->getUsers($clientPortalId);

        return response()->json(['client_portal_id' => $clientPortalId, 'number_of_users' => $numUsers], 200);
    }

    function getAdminAnalytics($clientPortalId){
        $clientPortal = \App\ClientPortal::find($clientPortalId);

        $error = [];

        if(!$clientPortal){
            $error['error'] = "Client portal with id `$clientPortalId` does not exists.";
            return response($error, 404);
        }

        $user = new User();
        $userData = $user->getAdminAnalytics($clientPortalId);

        return response()->json(['client_portal_id' => $clientPortalId, 'users' => $userData], 200);
    }
}