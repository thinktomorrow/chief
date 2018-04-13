<?php

/**
 * -----------------------------------------------------------------
 * SPIRIT ROUTES
 * -----------------------------------------------------------------
 */
Route::get('spirit/{section?}/{item?}', ['as' => 'spirit.index', 'uses' => function($section = null, $item = null){

    if($section && $item && view()->exists('spirit.'.$section.'.'.$item)){
        return view('spirit.'.$section.'.'.$item);
    }

    return view('spirit.home');
}]);

/**
 * -----------------------------------------------------------------
 * PROTOTYPING ROUTES
 * -----------------------------------------------------------------
 */
Route::get('prototype', function(){
    return view('prototype.article.create');
});

/**
 * -----------------------------------------------------------------
 * NON-AUTHENTICATED ADMIN ROUTES
 * -----------------------------------------------------------------
 */
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.store');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset.store');

/**
 * -----------------------------------------------------------------
 * ADMIN ROUTES
 * -----------------------------------------------------------------
 */
Route::group(['prefix' => 'admin','middleware' =>'auth:admin' ,'namespace' => 'back'],function()
{

    Route::get('logout', 'Auth\LoginController@logout')->name('back.logout');

    Route::get('/',function(){
        return view('back.home');
    })->name('back.dashboard');

//    Route::get('/',['as' => 'admin.home','uses' => HomeController::class.'@show']);


    // ARTICLES
    Route::get('articles','ArticlesController@index')->name('back.articles.index');
    Route::post('articles', 'ArticlesController@store')->name('back.articles.store');
    Route::get('articles/create', 'ArticlesController@create')->name('back.articles.create');
    Route::delete('articles/{article}', 'ArticlesController@destroy')->name('back.articles.destroy');
    Route::put('articles/{article}', 'ArticlesController@update')->name('back.articles.update');
    Route::get('articles/{article}', 'ArticlesController@show')->name('back.articles.show');
    Route::get('articles/{article}/edit', 'ArticlesController@edit')->name('back.articles.edit');
    Route::post('articles/publish','ArticlesController@publish')->name('back.articles.publish');

    Route::get('media', 'MediaLibraryController@library')->name('media.library');
    Route::get('media-modal', 'MediaLibraryController@mediaModal')->name('media.modal');
    Route::get('uploadtest', 'MediaLibraryController@uploadtest')->name('media.uploadtest');
    Route::post('media/upload', 'MediaController@upload')->name('media.upload');
    Route::post('media/remove', 'MediaController@remove')->name('media.remove');


    Route::post('article/{id}/upload', 'ArticlesController@upload')->name('article.upload');

    Route::get('/settings',['as' => 'back.settings.index','uses' => SettingsController::class.'@show']);

    Route::post('notes/publish',['as' => 'notes.publish','uses' => NoteController::class.'@publish']);
    Route::resource('notes', NoteController::class);

    //USER MANAGEMENT
    Route::get('users', 'UserController@index')->name('users.index')->middleware('permission:view_users');
    Route::post('users', 'UserController@store')->name('users.store')->middleware('permission:add_users');
    Route::get('users/create', 'UserController@create')->name('users.create')->middleware('permission:add_users');
    Route::delete('users/{user}', 'UserController@destroy')->name('users.destroy')->middleware('permission:delete_users');
    Route::put('users/{user}', 'UserController@update')->name('users.update')->middleware('permission:edit_users');
    Route::get('users/{user}', 'UserController@show')->name('users.show')->middleware('permission:view_users');
    Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit')->middleware('permission:edit_users');
    Route::post('users/{user}/publish', 'UserController@publish')->name('users.publish')->middleware('permission:edit_users');

    Route::get('roles', 'RoleController@index')->name('roles.index')->middleware('permission:view_roles');
    Route::post('roles', 'RoleController@store')->name('roles.store')->middleware('permission:add_roles');
    Route::get('roles/create', 'RoleController@create')->name('roles.create')->middleware('permission:add_roles');
    Route::delete('roles/{role}', 'RoleController@destroy')->name('roles.destroy')->middleware('permission:delete_roles');
    Route::put('roles/{role}', 'RoleController@update')->name('roles.update')->middleware('permission:edit_roles');
    Route::get('roles/{role}', 'RoleController@show')->name('roles.show')->middleware('permission:view_roles');
    Route::get('roles/{role}/edit', 'RoleController@edit')->name('roles.edit')->middleware('permission:edit_roles');

    Route::get('permissions', 'PermissionController@index')->name('permissions.index')->middleware('permission:view_permissions');
    Route::post('permissions', 'PermissionController@store')->name('permissions.store')->middleware('permission:add_permissions');
    Route::get('permissions/create', 'PermissionController@create')->name('permissions.create')->middleware('permission:add_permissions');
    Route::delete('permissions/{permission}', 'PermissionController@destroy')->name('permissions.destroy')->middleware('permission:delete_permissions');
    Route::put('permissions/{permission}', 'PermissionController@update')->name('permissions.update')->middleware('permission:edit_permissions');
    Route::get('permissions/{permission}', 'PermissionController@show')->name('permissions.show')->middleware('permission:view_permissions');
    Route::get('permissions/{permission}/edit', 'PermissionController@edit')->name('permissions.edit')->middleware('permission:edit_permissions');

    /**
     * -----------------------------------------------------------------
     * SQUANTO TRANSLATION ROUTES
     * -----------------------------------------------------------------
     */
    // Developer access
    Route::get('translations/lines/create',['middleware' => 'auth.superadmin', 'as' => 'squanto.lines.create','uses' => '\Thinktomorrow\Squanto\Manager\Http\Controllers\LineController@create']);
    Route::delete('translations/lines/{id}',['middleware' => 'auth.superadmin', 'as' => 'squanto.lines.destroy','uses' => '\Thinktomorrow\Squanto\Manager\Http\Controllers\LineController@destroy']);
    Route::get('translations/lines/{id}/edit',['middleware' => 'auth.superadmin', 'as' => 'squanto.lines.edit','uses' => '\Thinktomorrow\Squanto\Manager\Http\Controllers\LineController@edit']);
    Route::put('translations/lines/{id}',['middleware' => 'auth.superadmin', 'as' => 'squanto.lines.update','uses' => '\Thinktomorrow\Squanto\Manager\Http\Controllers\LineController@update']);
    Route::post('translations/lines',['middleware' => 'auth.superadmin', 'as' => 'squanto.lines.store','uses' => '\Thinktomorrow\Squanto\Manager\Http\Controllers\LineController@store']);

    // Client access
    Route::get('translations/{id}/edit',['as' => 'squanto.edit','uses' => '\Thinktomorrow\Squanto\Manager\Http\Controllers\TranslationController@edit']);
    Route::put('translations/{id}',['as' => 'squanto.update','uses' => '\Thinktomorrow\Squanto\Manager\Http\Controllers\TranslationController@update']);
    Route::get('translations',['as' => 'squanto.index','uses' => 'TranslationController@index']);

});
