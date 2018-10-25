<?php

Route::get('/', ["as" => 'home', 'uses' => 'PlayersController@home']);

Route::post('/update', ["as" => 'update', 'uses' => 'PlayersController@update']);

Route::get('/{gameSlug}', ["as" => 'game', 'uses' => 'PlayersController@game']);

