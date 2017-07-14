<?php

/**
 * -----------------------------------------------------------------
 * ADMIN ROUTES
 * -----------------------------------------------------------------
 */
Route::group(['prefix' => 'admin','middleware' =>'auth' ,'namespace' => 'Back'],function(){

    // ARTICLES
    Route::get('articles','ArticlesController@index')->name('admin.articles.index');
    Route::post('articles', 'ArticlesController@store')->name('articles.store');
    Route::get('articles/create', 'ArticlesController@create')->name('articles.create');
    Route::delete('articles/{article}', 'ArticlesController@destroy')->name('articles.destroy');
    Route::put('articles/{article}', 'ArticlesController@update')->name('articles.update');
    Route::get('articles/{article}', 'ArticlesController@show')->name('articles.show');
    Route::get('articles/{article}/edit', 'ArticlesController@edit')->name('articles.edit');
    Route::post('articles/publish','Articles\ArticleController@publish')->name('articles.publish');

    Route::get('media', function(){
      return view('back.media');
    });

    Route::get('media-modal', function(){
        return view('back.media-modal');
    });

    Route::get('uploadtest', function(){
        return view('back.uploadtest');
    });

    // FOR DEVS ONLY!
//    Route::get('translations/{slug}/lines/create',['middleware' => 'auth.superadmin', 'as' => 'back.trans.lines.create','uses' => '\Chief\Trans\Controllers\TranslationLineController@create']);
//    Route::post('translations/{slug}/lines',['middleware' => 'auth.superadmin', 'as' => 'back.trans.lines.store','uses' => '\Chief\Trans\Controllers\TranslationLineController@store']);
//    Route::get('translations/{slug}',['as' => 'back.trans.edit','uses' => '\Chief\Trans\Controllers\TranslationController@edit']);
//    Route::put('translations/{group_id}',['as' => 'back.trans.update','uses' => '\Chief\Trans\Controllers\TranslationController@update']);

    Route::get('/',['as' => 'admin.home','uses' => HomeController::class.'@show']);
    Route::get('/settings',['as' => 'admin.settings','uses' => SettingsController::class.'@show']);

    Route::get('users', 'UserController@index')->name('users.index')->middleware('permission:view_users');
    Route::post('users', 'UserController@store')->name('users.store')->middleware('permission:add_users');
    Route::get('users/create', 'UserController@create')->name('users.create')->middleware('permission:add_users');
    Route::delete('users/{user}', 'UserController@destroy')->name('users.destroy')->middleware('permission:delete_users');
    Route::put('users/{user}', 'UserController@update')->name('users.update')->middleware('permission:edit_users');
    Route::get('users/{user}', 'UserController@show')->name('users.show')->middleware('permission:view_users');
    Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit')->middleware('permission:edit_users');

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

});

/**
 * -----------------------------------------------------------------
 * PUBLIC LANGUAGE SWITCHER
 * -----------------------------------------------------------------
 */
Route::get('lang',['as' => 'lang.switch','uses' => Front\LanguageSwitcher::class.'@store']);

/**
 * -----------------------------------------------------------------
 * PUBLIC TRANSLATABLE ROUTES
 * -----------------------------------------------------------------
 */
Route::group(['prefix' => Locale::set(),'namespace' => 'Front'],function(){

    Route::get('/', ['as' => 'pages.home', 'uses' => function(){
        return view('front.home');
    }]);

});

// SETUP ROUTES
Route::get('/setup', function () {
    return view('setup.welcome');
});

Route::get('/',function(){
    return view('setup.welcome');
})->name('home');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');
Route::post('login', 'Auth\LoginController@login')->name('admin.login.store');
Route::post('logout', 'Auth\LoginController@logout')->name('admin.logout');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
