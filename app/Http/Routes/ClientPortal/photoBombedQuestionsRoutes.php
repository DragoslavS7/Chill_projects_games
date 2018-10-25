<?php

Route::get('/photo-bombed/questions', ['as' => 'photo-bombed.questions.index', 'uses' => 'PhotoBombedQuestionsController@index'] );
Route::get('/photo-bombed/questions/create', ['as' => 'photo-bombed.questions.create', 'uses' => 'PhotoBombedQuestionsController@create'] );
Route::post('/photo-bombed/questions', ['as' => 'photo-bombed.questions.store', 'uses' => 'PhotoBombedQuestionsController@store'] );

Route::post('/photo-bombed/questions/bulk-store', ['as' => 'photo-bombed.questions.bulk-store', 'uses' => 'PhotoBombedQuestionsController@bulkStore'] );

Route::get('/photo-bombed/questions/{id}/edit', ['as' => 'photo-bombed.questions.edit', 'uses' => 'PhotoBombedQuestionsController@edit'] );
Route::post('/photo-bombed/questions/{id}', ['as' => 'photo-bombed.questions.update', 'uses' => 'PhotoBombedQuestionsController@update'] );
Route::get('/photo-bombed/questions/{id}duplicate', ['as' => 'photo-bombed.questions.duplicate', 'uses' => 'PhotoBombedQuestionsController@duplicate'] );
Route::delete('/photo-bombed/questions/{id}', ['as' => 'photo-bombed.questions.delete', 'uses' => 'PhotoBombedQuestionsController@destroy'] );
Route::delete('/photo-bombed/questions/', ['as' => 'photo-bombed.questions.bulk-delete', 'uses' => 'PhotoBombedQuestionsController@destroyBulk'] );



Route::get('/photo-bombed/questions/data-tables', ['as' => 'photo-bombed.questions.data-tables', 'uses' => 'PhotoBombedQuestionsController@dataTables']);