<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('uniqueEmailClientPortalPair', function ($attribute,$value, $parameters,$validator) {
            $count = DB::table('users')->where('email', $value)
                ->where('client_portal_id', $parameters[0])
                ->count();

            return $count === 0;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
