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
    // Just to guide Johnny to the proper page - this route can be removed afterwards
    return redirect()->route('back.pages.create');
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
        return view('back.dashboard');
    })->name('back.dashboard');

//    Route::get('/',['as' => 'admin.home','uses' => HomeController::class.'@show']);


    // pageS
    Route::get('pages','Back\PagesController@index')->name('back.pages.index');
    Route::post('pages', 'Back\PagesController@store')->name('back.pages.store');
    Route::get('pages/create', 'Back\PagesController@create')->name('back.pages.create');
    Route::delete('pages/{id}', 'Back\PagesController@destroy')->name('back.pages.destroy');
    Route::put('pages/{id}', 'Back\PagesController@update')->name('back.pages.update');
    Route::get('pages/{id}', 'Back\PagesController@show')->name('back.pages.show');
    Route::get('pages/{id}/edit', 'Back\PagesController@edit')->name('back.pages.edit');
    Route::post('pages/publish','Back\PagesController@publish')->name('back.pages.publish');

    Route::get('media', 'Back\MediaLibraryController@library')->name('media.library');
    Route::get('media-modal', 'Back\MediaLibraryController@mediaModal')->name('media.modal');
    Route::get('uploadtest', 'Back\MediaLibraryController@uploadtest')->name('media.uploadtest');
    Route::post('media/upload', 'Back\MediaController@upload')->name('media.upload');
    Route::post('media/remove', 'Back\MediaController@remove')->name('media.remove');


    Route::post('page/{id}/upload', 'Back\PagesController@upload')->name('page.upload');

    Route::get('/settings',['as' => 'back.settings.index','uses' => Back\System\SettingsController::class.'@show']);

    Route::post('notes/publish',['as' => 'notes.publish','uses' => Back\NoteController::class.'@publish']);
    Route::resource('notes', Back\NoteController::class);

    /**
     * -----------------------------------------------------------------
     * AUTHORIZATION MANAGEMENT
     * -----------------------------------------------------------------
     */
    // USER MANAGEMENT
    Route::get('users', 'Back\Authorization\UserController@index')->name('back.users.index');
    Route::post('users', 'Back\Authorization\UserController@store')->name('back.users.store');
    Route::get('users/create', 'Back\Authorization\UserController@create')->name('back.users.create');
    Route::delete('users/{user}', 'Back\Authorization\UserController@destroy')->name('back.users.destroy');
    Route::put('users/{user}', 'Back\Authorization\UserController@update')->name('back.users.update');
    Route::get('users/{user}', 'Back\Authorization\UserController@show')->name('back.users.show');
    Route::get('users/{user}/edit', 'Back\Authorization\UserController@edit')->name('back.users.edit');
    Route::post('users/{user}/publish', 'Back\Authorization\UserController@publish')->name('back.users.publish');

    // ROLE MANAGEMENT
    Route::get('roles', 'Back\Authorization\RoleController@index')->name('back.roles.index');
    Route::post('roles', 'Back\Authorization\RoleController@store')->name('back.roles.store');
    Route::get('roles/create', 'Back\Authorization\RoleController@create')->name('back.roles.create');
    Route::delete('roles/{role}', 'Back\Authorization\RoleController@destroy')->name('back.roles.destroy');
    Route::put('roles/{role}', 'Back\Authorization\RoleController@update')->name('back.roles.update');
    Route::get('roles/{role}', 'Back\Authorization\RoleController@show')->name('back.roles.show');
    Route::get('roles/{role}/edit', 'Back\Authorization\RoleController@edit')->name('back.roles.edit');

    // PERMISSION MANAGEMENT
    Route::get('permissions', 'Back\Authorization\PermissionController@index')->name('back.permissions.index');
    Route::post('permissions', 'Back\Authorization\PermissionController@store')->name('back.permissions.store');
    Route::get('permissions/create', 'Back\Authorization\PermissionController@create')->name('back.permissions.create');
    Route::delete('permissions/{permission}', 'Back\Authorization\PermissionController@destroy')->name('back.permissions.destroy');
    Route::put('permissions/{permission}', 'Back\Authorization\PermissionController@update')->name('back.permissions.update');
    Route::get('permissions/{permission}', 'Back\Authorization\PermissionController@show')->name('back.permissions.show');
    Route::get('permissions/{permission}/edit', 'Back\Authorization\PermissionController@edit')->name('back.permissions.edit');

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
