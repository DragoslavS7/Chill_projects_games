<?php

namespace App\Http\Middleware;

use Closure;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiToken = $request->header('Api-Auth-Token');

        if ($apiToken != env('API_AUTH_TOKEN')) {
                return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}
