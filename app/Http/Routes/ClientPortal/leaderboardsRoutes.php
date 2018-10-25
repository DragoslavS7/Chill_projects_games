<?php

Route::get('games/leaderboard', ['as' => 'leaderboards.index', 'uses' => 'LeaderBoardsController@index'] );
Route::get('games/leaderboard/game/{game_id}', ['as' => 'leaderboards.game', 'uses' => 'LeaderBoardsController@game'] );
Route::delete('games/leaderboard/reset/{id}', ['as' => 'leaderboards.reset', 'uses' => 'LeaderBoardsController@reset'] );
Route::delete('games/leaderboard/reset-bulk/{id}', ['as' => 'leaderboards.bulk-reset', 'uses' => 'LeaderBoardsController@resetBulk'] );

Route::get('/leaderboard/data-tables-users/{id}', ['as' => 'leaderboard.data-tables-users', 'uses' => 'LeaderBoardsController@dataTablesUsers']);
Route::get('/leaderboard/data-tables-games', ['as' => 'leaderboard.data-tables-games', 'uses' => 'LeaderBoardsController@dataTablesGames']);

