<?php

Route::group([
    'as' => 'client-portal.',
    'namespace' => 'ClientPortal'
], function () {

    // Admin Routes
    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:uber_admin,admin,viewer']], function () {
        Route::get('/', function () {
            return redirect()->route('client-portal.games.index');
        })->name('home');

        require_once __DIR__ . '/questionsRoutes.php';
        require_once __DIR__ . '/quizzesRoutes.php';
        require_once __DIR__ . '/gamesRoutes.php';
        require_once __DIR__ . '/teamMembersRoutes.php';
        require_once __DIR__ . '/settingsRoutes.php';
        require_once __DIR__ . '/helpRoutes.php';
        require_once __DIR__ . '/gameAnalyticsRoutes.php';
        require_once __DIR__ . '/leaderboardsRoutes.php';
        require_once __DIR__ . '/adminAnalyticsRoutes.php';
        require_once __DIR__ . '/photoBombedQuestionsRoutes.php';
        require_once __DIR__ . '/photoBombedQuizzesRoutes.php';
    });

    // Player Routes
    Route::group(['as' => 'players.'], function () {
        require_once __DIR__ . '/playerRoutes.php';
    });
});