<?php

Route::group([
    'middleware' => ['auth:api']
], function () {
    Route::post('/notes', 'NotesController@store');
    Route::patch('/notes/{note}', 'NotesController@update');
    Route::delete('/notes/{note}', 'NotesController@destroy');
});

