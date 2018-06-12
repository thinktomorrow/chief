<?php

/**
 * -----------------------------------------------------------------
 * SPIRIT ROUTES
 * -----------------------------------------------------------------
 */
Route::get('spirit/{section?}/{item?}', ['as' => 'spirit.index', 'uses' => function ($section = null, $item = null) {
    if ($section && $item && view()->exists('chief::spirit.'.$section.'.'.$item)) {
        return view('chief::spirit.'.$section.'.'.$item);
    }

    return view('chief::spirit.home');
}])->middleware('web');


/**
 * -----------------------------------------------------------------
 * NON-AUTHENTICATED ADMIN ROUTES
 * -----------------------------------------------------------------
 */
Route::get('admin/login', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\LoginController@showLoginForm')->name('chief.back.login')->middleware('web');
Route::post('admin/login', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\LoginController@login')->name('chief.back.login.store')->middleware('web');

// Password Reset Routes...
Route::get('password/reset', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('chief.back.password.request')->middleware('web');
Route::post('password/email', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('chief.back.password.email')->middleware('web');
Route::get('password/reset/{token}', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('chief.back.password.reset')->middleware('web');
Route::post('password/reset', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ResetPasswordController@reset')->name('chief.back.password.reset.store')->middleware('web');

// Invitation routes...
Route::get('invite/expired', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\InviteController@expired')->name('invite.expired')->middleware('web');
Route::get('invite/{token}/accept', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\InviteController@accept')->name('invite.accept')->middleware('web');
Route::get('invite/{token}/deny', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\InviteController@deny')->name('invite.deny')->middleware('web');

/**
 * -----------------------------------------------------------------
 * ADMIN ROUTES
 * -----------------------------------------------------------------
 */
Route::group(['prefix' => 'admin','middleware' => ['web', 'web-chief', 'auth:chief']], function () {
    Route::get('logout', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\LoginController@logout')->name('chief.back.logout');

    Route::get('/', 'Thinktomorrow\Chief\App\Http\Controllers\Back\DashboardController@show')->name('chief.back.dashboard');
    Route::get('getting-started', 'Thinktomorrow\Chief\App\Http\Controllers\Back\DashboardController@gettingStarted')->name('chief.back.dashboard.getting-started');

    // Prompt for a (new) password
    Route::get('password-prompt', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ChangePasswordController@edit')->name('chief.back.password.edit');
    Route::put('password-prompt', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ChangePasswordController@update')->name('chief.back.password.update');

    // Pages
    Route::get('pages/{collection}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\PagesController@index')->name('chief.back.pages.index');
    Route::post('pages/{collection}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\PagesController@store')->name('chief.back.pages.store');
    Route::get('pages/{collection}/create', 'Thinktomorrow\Chief\App\Http\Controllers\Back\PagesController@create')->name('chief.back.pages.create');
    Route::put('pages/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\PagesController@update')->name('chief.back.pages.update');
    Route::get('pages/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\PagesController@show')->name('chief.back.pages.show')->where('id', '[0-9]+');
    Route::get('pages/{id}/edit', 'Thinktomorrow\Chief\App\Http\Controllers\Back\PagesController@edit')->name('chief.back.pages.edit');
    Route::delete('pages/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\PagesController@destroy')->name('chief.back.pages.destroy');
    Route::post('pages/{id}/publish', 'Thinktomorrow\Chief\App\Http\Controllers\Back\PagesController@publish')->name('chief.back.pages.publish');


    Route::get('media', 'Thinktomorrow\Chief\App\Http\Controllers\Back\MediaLibraryController@library')->name('media.library');
    Route::get('media-modal', 'Thinktomorrow\Chief\App\Http\Controllers\Back\MediaLibraryController@mediaModal')->name('media.modal');
    Route::get('uploadtest', 'Thinktomorrow\Chief\App\Http\Controllers\Back\MediaLibraryController@uploadtest')->name('media.uploadtest');
    Route::post('media/upload', 'Thinktomorrow\Chief\App\Http\Controllers\Back\MediaController@upload')->name('media.upload');
    Route::post('media/remove', 'Thinktomorrow\Chief\App\Http\Controllers\Back\MediaController@remove')->name('media.remove');

    Route::get('menu', 'Thinktomorrow\Chief\App\Http\Controllers\Back\MenuController@index')->name('chief.back.menu.index');
    Route::post('menu', 'Thinktomorrow\Chief\App\Http\Controllers\Back\MenuController@store')->name('chief.back.menu.store');    
    Route::get('menu/create', 'Thinktomorrow\Chief\App\Http\Controllers\Back\MenuController@create')->name('chief.back.menu.create');
    Route::get('menu/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\MenuController@update')->name('chief.back.menu.update');
    Route::get('menu/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\MenuController@show')->name('chief.back.menu.show');
    Route::get('menu/{id}/edit', 'Thinktomorrow\Chief\App\Http\Controllers\Back\MenuController@edit')->name('chief.back.menu.edit');

    Route::post('page/{id}/upload', 'Thinktomorrow\Chief\App\Http\Controllers\Back\PagesController@upload')->name('page.upload');

    Route::get('/settings', ['as' => 'chief.back.settings.index','uses' => Thinktomorrow\Chief\App\Http\Controllers\Back\System\SettingsController::class.'@show']);

    Route::post('notes/publish', ['as' => 'notes.publish','uses' => Thinktomorrow\Chief\App\Http\Controllers\Back\NoteController::class.'@publish']);
    Route::resource('notes', Thinktomorrow\Chief\App\Http\Controllers\Back\NoteController::class);

    // YOUR PROFILE MANAGEMENT
    Route::get('you', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\YouController@edit')->name('chief.back.you.edit');
    Route::put('you', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\YouController@update')->name('chief.back.you.update');

    /**
     * -----------------------------------------------------------------
     * AUTHORIZATION MANAGEMENT
     * -----------------------------------------------------------------
     */
    // USER MANAGEMENT
    Route::get('users', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\UserController@index')->name('chief.back.users.index');
    Route::post('users', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\UserController@store')->name('chief.back.users.store');
    Route::get('users/create', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\UserController@create')->name('chief.back.users.create');
//    Route::delete('users/{user}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\UserController@destroy')->name('chief.back.users.destroy');
    Route::put('users/{user}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\UserController@update')->name('chief.back.users.update');
    Route::get('users/{user}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\UserController@show')->name('chief.back.users.show');
    Route::get('users/{user}/edit', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\UserController@edit')->name('chief.back.users.edit');

    // INVITE MANAGEMENT
    Route::post('users/{id}/disable', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\DisableUserController@store')->name('chief.back.users.disable');
    Route::post('users/{id}/enable', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\EnableUserController@store')->name('chief.back.users.enable');
    Route::get('users/{id}/resend-invite', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\ResendInviteController@store')->name('chief.back.invites.resend');

    // ROLE MANAGEMENT
    Route::get('roles', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Authorization\RoleController@index')->name('chief.back.roles.index');
    Route::post('roles', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Authorization\RoleController@store')->name('chief.back.roles.store');
    Route::get('roles/create', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Authorization\RoleController@create')->name('chief.back.roles.create');
    Route::delete('roles/{role}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Authorization\RoleController@destroy')->name('chief.back.roles.destroy');
    Route::put('roles/{role}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Authorization\RoleController@update')->name('chief.back.roles.update');
    Route::get('roles/{role}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Authorization\RoleController@show')->name('chief.back.roles.show');
    Route::get('roles/{role}/edit', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Authorization\RoleController@edit')->name('chief.back.roles.edit');

    // PERMISSION MANAGEMENT
    Route::get('permissions', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Authorization\PermissionController@index')->name('chief.back.permissions.index');
    Route::post('permissions', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Authorization\PermissionController@store')->name('chief.back.permissions.store');
    Route::get('permissions/create', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Authorization\PermissionController@create')->name('chief.back.permissions.create');
    Route::delete('permissions/{permission}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Authorization\PermissionController@destroy')->name('chief.back.permissions.destroy');
    Route::put('permissions/{permission}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Authorization\PermissionController@update')->name('chief.back.permissions.update');
    Route::get('permissions/{permission}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Authorization\PermissionController@show')->name('chief.back.permissions.show');
    Route::get('permissions/{permission}/edit', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Authorization\PermissionController@edit')->name('chief.back.permissions.edit');

    /**
     * -----------------------------------------------------------------
     * SQUANTO TRANSLATION ROUTES
     * -----------------------------------------------------------------
     */
    // Developer access
    Route::get('translations/lines/create', ['middleware' => 'auth.superadmin', 'as' => 'squanto.lines.create','uses' => '\Thinktomorrow\Squanto\Manager\Http\Controllers\LineController@create']);
    Route::delete('translations/lines/{id}', ['middleware' => 'auth.superadmin', 'as' => 'squanto.lines.destroy','uses' => '\Thinktomorrow\Squanto\Manager\Http\Controllers\LineController@destroy']);
    Route::get('translations/lines/{id}/edit', ['middleware' => 'auth.superadmin', 'as' => 'squanto.lines.edit','uses' => '\Thinktomorrow\Squanto\Manager\Http\Controllers\LineController@edit']);
    Route::put('translations/lines/{id}', ['middleware' => 'auth.superadmin', 'as' => 'squanto.lines.update','uses' => '\Thinktomorrow\Squanto\Manager\Http\Controllers\LineController@update']);
    Route::post('translations/lines', ['middleware' => 'auth.superadmin', 'as' => 'squanto.lines.store','uses' => '\Thinktomorrow\Squanto\Manager\Http\Controllers\LineController@store']);

    // Client access
    Route::get('translations/{id}/edit', ['as' => 'squanto.edit','uses' => '\Thinktomorrow\Squanto\Manager\Http\Controllers\TranslationController@edit']);
    Route::put('translations/{id}', ['as' => 'squanto.update','uses' => '\Thinktomorrow\Squanto\Manager\Http\Controllers\TranslationController@update']);
    Route::get('translations', ['as' => 'squanto.index','uses' => 'Thinktomorrow\Chief\App\Http\Controllers\Back\TranslationController@index']);
});
