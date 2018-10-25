<?php


Route::group([
    'middleware' => 'api',
    'as' => 'api.',
    'namespace' => 'API',
    'domain' => env('API_SUB_DOMAIN') . '.' . env('APP_URL')
], function () {

    Route::group([
        'prefix' => '/api'
    ], function () {

        // Game routes
        Route::get('/games/{gameId}', ['as' => 'games.view', 'uses' => 'GameController@getGame']);


        //Client portal analytics
        Route::group([
            'as'=>'client-portal.'
        ], function () {
            // Games analytics
            Route::get('/client-portal/{clientPortalId}/analytics/average-duration', ['as' => 'games.average-duration', 'uses' => 'GameController@getGameAvgTime']);
            Route::get('/client-portal/{clientPortalId}/analytics/average-score', ['as' => 'games.average-score', 'uses' => 'GameController@getGameAvgScore']);
            Route::get('/client-portal/{clientPortalId}/analytics/games', ['as' => 'games.analytics', 'uses' => 'GameController@getGameAnalytics']);

            //User analytics
            Route::get('/client-portal/{clientPortalId}/analytics/users', ['as' => 'users.number', 'uses' => 'ClientPortal\UsersController@getUsers']);
            Route::get('/client-portal/{clientPortalId}/analytics/admin', ['as' => 'users.admin', 'uses' => 'ClientPortal\UsersController@getAdminAnalytics']);

            //Quiz analytics
            Route::get('/client-portal/{clientPortalId}/analytics/quizzes', ['as' => 'quizzes', 'uses' => 'ClientPortal\QuizController@getQuizzes']);
        });


        // Experience API routes
        Route::post('/experience/games/{gameId}', ['as' => 'experience.games.finish', 'uses' => 'ExperienceController@gameFinish']);
        Route::post('/experience/games/start/{gameId}', ['as' => 'experience.games.start', 'uses' => 'ExperienceController@gameStart']);
        Route::post('/experience/quizzes/{quizId}', ['as' => 'experience.quizzes.finish', 'uses' => 'ExperienceController@quizFinish']);
        Route::post('/experience/quizzes/start/{quizId}', ['as' => 'experience.quizzes.start', 'uses' => 'ExperienceController@quizStart']);
        Route::post('/experience/questions/{questionId}', ['as' => 'experience.questions.finish', 'uses' => 'ExperienceController@questionFinish']);


    });

    // Return 404 to all routes that do not exists
    Route::any('{any}', function(){
        $error = [];
        $error['error'] = "Requested endpoint do not exist.";
        $error['endpoint'] = request()->fullUrl();

        return response()->json($error, 404);
    })->where('any', '.*')->middleware('api');

});

