<?php

Route::get('/game-analytics', ['as' => 'game-analytics.index', 'uses' => 'GameAnalyticsController@index'] );
Route::get('/game-analytics/game/{id}', ['as' => 'game-analytics.game', 'uses' => 'GameAnalyticsController@game'] );
Route::get('/game-analytics/report', ['as' => 'game-analytics.report', 'uses' => 'GameAnalyticsController@report'] );

Route::get('/game-analytics/data-table', ['as' => 'game-analytics.data-table', 'uses' => 'GameAnalyticsController@dataTable']);
Route::get('/game-analytics/data-tables-users/{id}', ['as' => 'game-analytics.data-tables-users', 'uses' => 'GameAnalyticsController@dataTablesUsers']);
Route::get('/game-analytics/data-tables-questions/{id}', ['as' => 'game-analytics.data-tables-questions', 'uses' => 'GameAnalyticsController@dataTablesQuestions']);

