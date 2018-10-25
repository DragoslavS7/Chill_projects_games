<?php

Route::group([
    'domain' => env('UBER_ADMIN_SUB_DOMAIN') . '.' . env('APP_URL'),
    'as' => 'admin-portal.',
    'namespace' => 'AdminPortal',
    'middleware' => ['auth', 'role:uber_admin']
    ], function(){

    Route::get('/', function(){
        return redirect()->route('admin-portal.client-portals.index');
    })->name('home');

    require_once __DIR__ . '/clientPortalsRoutes.php';
    require_once __DIR__ . '/gameTemplatesRoutes.php';
    require_once __DIR__ . '/teamMembersRoutes.php';
    require_once __DIR__ . '/gameAnalyticsRoutes.php';
});