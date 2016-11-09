<?php

/**
 * -----------------------------------------------------------------
 * ADMIN ROUTES
 * -----------------------------------------------------------------
 */
Route::group(['prefix' => 'back','middleware' =>['web','auth']],function(){

    // Developer access
    Route::get('translations/lines/create',['middleware' => 'auth.superadmin', 'as' => 'back.squanto.lines.create','uses' => '\Chief\SquantoManager\Controllers\LineController@create']);
    Route::delete('translations/lines/{id}',['middleware' => 'auth.superadmin', 'as' => 'back.squanto.lines.destroy','uses' => '\Chief\SquantoManager\Controllers\LineController@destroy']);
    Route::get('translations/lines/{id}/edit',['middleware' => 'auth.superadmin', 'as' => 'back.squanto.lines.edit','uses' => '\Chief\SquantoManager\Controllers\LineController@edit']);
    Route::put('translations/lines/{id}',['middleware' => 'auth.superadmin', 'as' => 'back.squanto.lines.update','uses' => '\Chief\SquantoManager\Controllers\LineController@update']);
    Route::post('translations/lines',['middleware' => 'auth.superadmin', 'as' => 'back.squanto.lines.store','uses' => '\Chief\SquantoManager\Controllers\LineController@store']);

    // Client access
    Route::get('translations/{id}/edit',['as' => 'back.squanto.edit','uses' => '\Chief\SquantoManager\Controllers\TranslationController@edit']);
    Route::put('translations/{id}',['as' => 'back.squanto.update','uses' => '\Chief\SquantoManager\Controllers\TranslationController@update']);
    Route::get('translations',['as' => 'back.squanto.index','uses' => '\Chief\SquantoManager\Controllers\TranslationController@index']);

});