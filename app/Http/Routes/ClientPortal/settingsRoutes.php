<?php

Route::get('/settings', ['as' => 'settings.index', 'uses' => 'SettingsController@index'] );
Route::get('/settings/{id}', ['as' => 'settings.update', 'uses' => 'SettingsController@update'] );
Route::post('/settings', ['as' => 'settings.store', 'uses' => 'SettingsController@store'] );
