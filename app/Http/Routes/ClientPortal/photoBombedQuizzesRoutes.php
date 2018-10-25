<?php

Route::get('/photo-bombed/quizzes', ['as' => 'photo-bombed.quizzes.index', 'uses' => 'PhotoBombedQuizzesController@index'] );
Route::get('/photo-bombed/quizzes/create', ['as' => 'photo-bombed.quizzes.create', 'uses' => 'PhotoBombedQuizzesController@create'] );
Route::post('/photo-bombed/quizzes', ['as' => 'photo-bombed.quizzes.store', 'uses' => 'PhotoBombedQuizzesController@store'] );

Route::get('/photo-bombed/quizzes/{id}/edit', ['as' => 'photo-bombed.quizzes.edit', 'uses' => 'PhotoBombedQuizzesController@edit'] );
Route::post('/photo-bombed/quizzes/{id}', ['as' => 'photo-bombed.quizzes.update', 'uses' => 'PhotoBombedQuizzesController@update'] );
Route::get('/photo-bombed/quizzes/{id}/duplicate', ['as' => 'photo-bombed.quizzes.duplicate', 'uses' => 'PhotoBombedQuizzesController@duplicate'] );
Route::delete('/photo-bombed/quizzes/{id}', ['as' => 'photo-bombed.quizzes.delete', 'uses' => 'PhotoBombedQuizzesController@destroy'] );
Route::delete('/photo-bombed/quizzes/', ['as' => 'photo-bombed.quizzes.bulk-delete', 'uses' => 'PhotoBombedQuizzesController@destroyBulk'] );

Route::get('/photo-bombed/quizzes/tags', ['as' => 'photo-bombed.quizzes.tags', 'uses' => 'PhotoBombedQuizzesController@tags'] );

Route::get('/photo-bombed/quizzes/data-tables', ['as' => 'photo-bombed.quizzes.data-tables', 'uses' => 'PhotoBombedQuizzesController@dataTables']);