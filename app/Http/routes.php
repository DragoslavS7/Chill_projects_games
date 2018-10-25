<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'subDomain'], function () {

    if(subDomain() != env('API_SUB_DOMAIN')) {
        // Guest routes
        Route::group(['middleware' => 'guest'], function () {
            Route::get('/login', ['as' => 'user.auth.index', 'uses' => 'Auth\AuthController@login']);
            Route::post('/login', ['as' => 'user.auth.login', 'uses' => 'Auth\AuthController@doLogin']);
            Route::get('/login/forgot', ['as' => 'user.auth.forgot-password', 'uses' => 'Auth\PasswordController@forgotPassword']);

            Route::post('email/password/reset/request', ['as' => 'user.auth.password-reset-request', 'uses' => 'Auth\PasswordController@passwordResetRequest']);
            Route::get('password-reset-request/{token}', ['as' => 'user.auth.password-reset-form', 'uses' => 'Auth\PasswordController@passwordResetForm']);
            Route::post('password/reset', ['as' => 'user.auth.password-reset', 'uses' => 'Auth\PasswordController@passwordReset']);
        });

        Route::get('/user/verify/{token}', ['as' => 'user.verify', 'uses' => 'Auth\AuthController@verifyAccount'] );
        Route::get('/user/resend/{userId}', ['as' => 'user.resend', 'uses' => 'Auth\AuthController@resendVerification'] );

        // Register users routes
        Route::group(['middleware' => 'auth'], function () {
            Route::get('/not-authorized', ['as' => 'user.not-not-authorized', function(){
                return view('auth.not-authorized');
            }]);

            Route::get('/logout', ['as' => 'user.auth.logout', 'uses' => 'Auth\AuthController@doLogout']);
        });
    }

    //API Routes
    require_once __DIR__ . '/Routes/API/indexRoutes.php';

    // Admin Portal Routes
    if(subDomain() == env('UBER_ADMIN_SUB_DOMAIN')) {
        require_once __DIR__ . '/Routes/AdminPortal/indexRoutes.php';
    }

    // Client Portal Routes
    if(!in_array(subDomain(), [env('API_SUB_DOMAIN'), env('UBER_ADMIN_SUB_DOMAIN')])) {
        require_once __DIR__ . '/Routes/ClientPortal/indexRoutes.php';
    }

});