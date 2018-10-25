<?php

Route::get('/clients', ['as' => 'client-portals.index', 'uses' => 'ClientPortalsController@index']);
Route::get('/clients/create', ['as' => 'client-portals.create', 'uses' => 'ClientPortalsController@create']);
Route::post('/clients', ['as' => 'client-portals.store', 'uses' => 'ClientPortalsController@store']);

Route::get('/clients/{id}/edit', ['as' => 'client-portals.edit', 'uses' => 'ClientPortalsController@edit']);
Route::get('/clients/{id}/view', ['as' => 'client-portals.view', 'uses' => 'ClientPortalsController@view']);
Route::post('/clients/{id}', ['as' => 'client-portals.update', 'uses' => 'ClientPortalsController@update']);
Route::delete('/clients/{id}', ['as' => 'client-portals.delete', 'uses' => 'ClientPortalsController@destroy']);
Route::delete('/clients', ['as' => 'client-portals.bulk-delete', 'uses' => 'ClientPortalsController@destroyBulk']);

Route::get('/clients/data-tables', ['as' => 'client-portals.data-tables', 'uses' => 'ClientPortalsController@getClientPortalsDataTables']);
Route::get('/clients/data-tables/templates/{id}', ['as' => 'client-portals.data-tables-templates', 'uses' => 'ClientPortalsController@dataTablesTemplatesAssignedOnly']);