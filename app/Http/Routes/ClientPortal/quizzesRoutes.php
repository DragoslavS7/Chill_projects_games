<?php

Route::get('/quizzes', ['as' => 'quizzes.index', 'uses' => 'QuizzesController@index'] );
Route::get('/quizzes/create', ['as' => 'quizzes.create', 'uses' => 'QuizzesController@create'] );
Route::post('/quizzes', ['as' => 'quizzes.store', 'uses' => 'QuizzesController@store'] );

Route::get('/quizzes/{id}/edit', ['as' => 'quizzes.edit', 'uses' => 'QuizzesController@edit'] );
Route::post('/quizzes/{id}', ['as' => 'quizzes.update', 'uses' => 'QuizzesController@update'] );
Route::get('/quizzes/{id}/duplicate', ['as' => 'quizzes.duplicate', 'uses' => 'QuizzesController@duplicate'] );
Route::delete('/quizzes/{id}', ['as' => 'quizzes.delete', 'uses' => 'QuizzesController@destroy'] );
Route::delete('/quizzes/', ['as' => 'quizzes.bulk-delete', 'uses' => 'QuizzesController@destroyBulk'] );

Route::get('/quizzes/tags', ['as' => 'quizzes.tags', 'uses' => 'QuizzesController@tags'] );

Route::get('/quizzes/data-tables', ['as' => 'quizzes.data-tables', 'uses' => 'QuizzesController@dataTables']);