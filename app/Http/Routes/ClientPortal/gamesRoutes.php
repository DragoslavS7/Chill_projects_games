<?php

Route::get('/games', ['as' => 'games.index', 'uses' => 'GamesController@index'] );
Route::get('/games/templates', ['as' => 'games.templates', 'uses' => 'GamesController@templates'] );
Route::get('/games/templates/filter', ['as' => 'games.templates.filter', 'uses' => 'GamesController@filterGameTemplates'] );
Route::get('/games/{templateId}/create', ['as' => 'games.create', 'uses' => 'GamesController@create'] );
Route::post('/games', ['as' => 'games.store', 'uses' => 'GamesController@store'] );

Route::get('/games/{id}/edit', ['as' => 'games.edit', 'uses' => 'GamesController@edit'] );
Route::post('/games/{id}', ['as' => 'games.update', 'uses' => 'GamesController@update'] );
Route::delete('/games/{id}', ['as' => 'games.delete', 'uses' => 'GamesController@destroy'] );
Route::delete('/games/', ['as' => 'games.bulk-delete', 'uses' => 'GamesController@destroyBulk'] );

Route::post('/games-bulk-invite', ['as' => 'games.bulk-invite', 'uses' => 'GamesController@inviteBulk'] );


Route::get('/games/data-tables', ['as' => 'games.data-tables', 'uses' => 'GamesController@dataTables']);
Route::get('/games/data-tables/analytics', ['as' => 'games.data-tables.analytics', 'uses' => 'GamesController@dataTablesAnalytics']);
Route::get('/games/{templateId}/name', ['as' => 'games.game-template-name', 'uses' => 'GamesController@getTemplateName']);
