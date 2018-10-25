<?php

Route::get('/team-members', ['as' => 'team-members.index', 'uses' => 'TeamMembersController@index'] );
Route::get('/team-members/create', ['as' => 'team-members.create', 'uses' => 'TeamMembersController@create'] );
Route::post('/team-members', ['as' => 'team-members.store', 'uses' => 'TeamMembersController@store'] );
Route::post('/team-members-bulk', ['as' => 'team-members.bulk-store', 'uses' => 'TeamMembersController@bulkStore'] );

Route::get('/team-members/{id}/edit', ['as' => 'team-members.edit', 'uses' => 'TeamMembersController@edit'] );
Route::get('/team-members/{id}/verify', ['as' => 'team-members.resend-verify', 'uses' => 'TeamMembersController@resendVerify'] );
Route::post('/team-members/{id}', ['as' => 'team-members.update', 'uses' => 'TeamMembersController@update'] );
Route::delete('/team-members/{id}', ['as' => 'team-members.delete', 'uses' => 'TeamMembersController@destroy'] );
Route::delete('/team-members', ['as' => 'team-members.bulk-delete', 'uses' => 'TeamMembersController@destroyBulk'] );

Route::get('/team-members/data-tables', ['as' => 'team-members.data-tables', 'uses' => 'TeamMembersController@dataTables']);