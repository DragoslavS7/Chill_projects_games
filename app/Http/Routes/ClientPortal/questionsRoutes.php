<?php

Route::get('/questions', ['as' => 'questions.index', 'uses' => 'QuestionsController@index'] );
Route::get('/questions/create', ['as' => 'questions.create', 'uses' => 'QuestionsController@create'] );
Route::post('/questions', ['as' => 'questions.store', 'uses' => 'QuestionsController@store'] );

Route::post('/questions/bulk-store', ['as' => 'questions.bulk-store', 'uses' => 'QuestionsController@bulkStore'] );

Route::get('/questions/{id}/edit', ['as' => 'questions.edit', 'uses' => 'QuestionsController@edit'] );
Route::post('/questions/{id}', ['as' => 'questions.update', 'uses' => 'QuestionsController@update'] );
Route::get('/questions/{id}duplicate', ['as' => 'questions.duplicate', 'uses' => 'QuestionsController@duplicate'] );
Route::delete('/questions/{id}', ['as' => 'questions.delete', 'uses' => 'QuestionsController@destroy'] );
Route::delete('/questions/', ['as' => 'questions.bulk-delete', 'uses' => 'QuestionsController@destroyBulk'] );



Route::get('/questions/data-tables', ['as' => 'questions.data-tables', 'uses' => 'QuestionsController@dataTables']);