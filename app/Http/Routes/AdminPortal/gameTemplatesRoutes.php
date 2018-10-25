<?php

Route::get('/game-templates', ['as' => 'game-templates.index', 'uses' => 'GameTemplatesController@index']);
Route::get('/game-templates/create', ['as' => 'game-templates.create', 'uses' => 'GameTemplatesController@create']);
Route::post('/game-templates', ['as' => 'game-templates.store', 'uses' => 'GameTemplatesController@store']);

Route::get('/game-templates/{id}/edit', ['as' => 'game-templates.edit', 'uses' => 'GameTemplatesController@edit'])->where('id', '[0-9]+');
Route::get('/game-templates/{id}/view', ['as' => 'game-templates.view', 'uses' => 'GameTemplatesController@view'])->where('id', '[0-9]+');
Route::post('/game-templates/{id}', ['as' => 'game-templates.update', 'uses' => 'GameTemplatesController@update']);
Route::delete('/game-templates/{id}', ['as' => 'game-templates.delete', 'uses' => 'GameTemplatesController@destroy']);
Route::delete('/game-templates/', ['as' => 'game-templates.bulk-delete', 'uses' => 'GameTemplatesController@destroyBulk']);

Route::get('/game-templates/data-tables', ['as' => 'game-templates.data-tables', 'uses' => 'GameTemplatesController@getGameTemplatesDataTables']);