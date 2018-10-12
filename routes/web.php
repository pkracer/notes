<?php

Auth::routes();

Route::group([
    'middleware' => ['auth']
], function () {
    Route::get('/', function () {
        $notes = request()->user()->notes()->latest('updated_at')->get();

        return view('welcome', compact('notes'));
    });
});




