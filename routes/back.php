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
 * NON-AUTHENTICATED ADMIN ROUTES
 * -----------------------------------------------------------------
 */
Route::get('admin/login', 'Auth\LoginController@showLoginForm')->name('back.login');
Route::post('admin/login', 'Auth\LoginController@login')->name('back.login.store');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('back.password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('back.password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('back.password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('back.password.reset.store');

/**
 * -----------------------------------------------------------------
 * PROTOTYPING ROUTES
 * -----------------------------------------------------------------
 */
Route::get('prototype', function(){
    return view('prototype.index');
});

/**
 * -----------------------------------------------------------------
 * ADMIN ROUTES
 * -----------------------------------------------------------------
 */
Route::group(['prefix' => 'admin','middleware' =>'auth:admin'],function()
{

    Route::get('logout', 'Auth\LoginController@logout')->name('back.logout');

    Route::get('/',function(){
        return view('back.home');
    })->name('back.dashboard');

//    Route::get('/',['as' => 'admin.home','uses' => HomeController::class.'@show']);


    // ARTICLES
    Route::get('articles','Back\ArticlesController@index')->name('back.articles.index');
    Route::post('articles', 'Back\ArticlesController@store')->name('back.articles.store');
    Route::get('articles/create', 'Back\ArticlesController@create')->name('back.articles.create');
    Route::delete('articles/{article}', 'Back\ArticlesController@destroy')->name('back.articles.destroy');
    Route::put('articles/{article}', 'Back\ArticlesController@update')->name('back.articles.update');
    Route::get('articles/{article}', 'Back\ArticlesController@show')->name('back.articles.show');
    Route::get('articles/{article}/edit', 'Back\ArticlesController@edit')->name('back.articles.edit');
    Route::post('articles/publish','Back\ArticlesController@publish')->name('back.articles.publish');

    Route::get('media', 'Back\MediaLibraryController@library')->name('media.library');
    Route::get('media-modal', 'Back\MediaLibraryController@mediaModal')->name('media.modal');
    Route::get('uploadtest', 'Back\MediaLibraryController@uploadtest')->name('media.uploadtest');
    Route::post('media/upload', 'Back\MediaController@upload')->name('media.upload');
    Route::post('media/remove', 'Back\MediaController@remove')->name('media.remove');


    Route::post('article/{id}/upload', 'Back\ArticlesController@upload')->name('article.upload');

    Route::get('/settings',['as' => 'back.settings.index','uses' => SettingsController::class.'@show']);

    Route::post('notes/publish',['as' => 'notes.publish','uses' => NoteController::class.'@publish']);
    Route::resource('notes', NoteController::class);

    //USER MANAGEMENT
    Route::get('users', ' Back\UserController@index')->name('users.index')->middleware('permission:view_users');
    Route::post('users', ' Back\UserController@store')->name('users.store')->middleware('permission:add_users');
    Route::get('users/create', ' Back\UserController@create')->name('users.create')->middleware('permission:add_users');
    Route::delete('users/{user}', ' Back\UserController@destroy')->name('users.destroy')->middleware('permission:delete_users');
    Route::put('users/{user}', ' Back\UserController@update')->name('users.update')->middleware('permission:edit_users');
    Route::get('users/{user}', ' Back\UserController@show')->name('users.show')->middleware('permission:view_users');
    Route::get('users/{user}/edit', ' Back\UserController@edit')->name('users.edit')->middleware('permission:edit_users');
    Route::post('users/{user}/publish', ' Back\UserController@publish')->name('users.publish')->middleware('permission:edit_users');

    Route::get('roles', 'Back\RoleController@index')->name('roles.index')->middleware('permission:view_roles');
    Route::post('roles', 'Back\RoleController@store')->name('roles.store')->middleware('permission:add_roles');
    Route::get('roles/create', 'Back\RoleController@create')->name('roles.create')->middleware('permission:add_roles');
    Route::delete('roles/{role}', 'Back\RoleController@destroy')->name('roles.destroy')->middleware('permission:delete_roles');
    Route::put('roles/{role}', 'Back\RoleController@update')->name('roles.update')->middleware('permission:edit_roles');
    Route::get('roles/{role}', 'Back\RoleController@show')->name('roles.show')->middleware('permission:view_roles');
    Route::get('roles/{role}/edit', 'Back\RoleController@edit')->name('roles.edit')->middleware('permission:edit_roles');

    Route::get('permissions', 'Back\PermissionController@index')->name('permissions.index')->middleware('permission:view_permissions');
    Route::post('permissions', 'Back\PermissionController@store')->name('permissions.store')->middleware('permission:add_permissions');
    Route::get('permissions/create', 'Back\PermissionController@create')->name('permissions.create')->middleware('permission:add_permissions');
    Route::delete('permissions/{permission}', 'Back\PermissionController@destroy')->name('permissions.destroy')->middleware('permission:delete_permissions');
    Route::put('permissions/{permission}', 'Back\PermissionController@update')->name('permissions.update')->middleware('permission:edit_permissions');
    Route::get('permissions/{permission}', 'Back\PermissionController@show')->name('permissions.show')->middleware('permission:view_permissions');
    Route::get('permissions/{permission}/edit', 'Back\PermissionController@edit')->name('permissions.edit')->middleware('permission:edit_permissions');

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
    Route::get('translations',['as' => 'squanto.index','uses' => 'Back\TranslationController@index']);

});

