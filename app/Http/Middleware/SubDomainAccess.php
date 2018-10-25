<?php

namespace App\Http\Middleware;

use App\ClientPortal;
use Closure;
use Illuminate\Support\Facades\Auth;

class SubDomainAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $subDomain = subDomain();


        $clientPortal = ClientPortal::where('sub_domain', $subDomain)->first();

        // Check if sub domain is not admin panel and exists and it is not api portal
        if ($subDomain != env('UBER_ADMIN_SUB_DOMAIN') && !$clientPortal && $subDomain != env('API_SUB_DOMAIN')) {
            return abort(404, 'This sub-domain do not exist.');
        }else{
            $user = Auth::user();

            if($user && $clientPortal){
                // Check if user is on right sub domain
                if($user->client_portal_id != $clientPortal->id){

                    if($user->isUberAdmin()){
                        // Check if costumer service is enabled
                        if(!$clientPortal->is_costumer_service_available) {
                            return abort(404, "Costumer service for this client is not enabled.");
                        }

                    }else{
                        Auth::logout();
                        return abort(404, "You are on wrong sub domain");
                    }

                }
            }

        }


        $request->clientPortal = $clientPortal;

        return $next($request);
    }
}