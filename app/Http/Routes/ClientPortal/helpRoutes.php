<?php

Route::get('/support', ['as' => 'help.index', 'uses' => 'HelpController@index'] );
Route::get('/documentation', ['as' => 'help.documentation', 'uses' => 'HelpController@documentation'] );


