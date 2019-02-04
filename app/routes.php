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
Route::get('admin/password/reset', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('chief.back.password.request')->middleware('web');
Route::post('admin/password/email', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('chief.back.password.email')->middleware('web');
Route::get('admin/password/reset/{token}', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('chief.back.password.reset')->middleware('web');
Route::post('admin/password/reset', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ResetPasswordController@reset')->name('chief.back.password.reset.store')->middleware('web');

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

    // Dashboard
    Route::get('/', 'Thinktomorrow\Chief\App\Http\Controllers\Back\DashboardController@show')->name('chief.back.dashboard');

    /**
     * -----------------------------------------------------------------
     * MANAGER ROUTES
     * -----------------------------------------------------------------
     */
    Route::get('manage/{key}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\ManagersController@index')->name('chief.back.managers.index');
    Route::get('manage/{key}/create', 'Thinktomorrow\Chief\App\Http\Controllers\Back\ManagersController@create')->name('chief.back.managers.create');
    Route::post('manage/{key}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\ManagersController@store')->name('chief.back.managers.store');
    Route::put('manage/{key}/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\ManagersController@update')->name('chief.back.managers.update')->where('id', '[0-9]+');
    Route::get('manage/{key}/{id}/edit', 'Thinktomorrow\Chief\App\Http\Controllers\Back\ManagersController@edit')->name('chief.back.managers.edit')->where('id', '[0-9]+');
    Route::delete('manage/{key}/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\ManagersController@delete')->name('chief.back.managers.delete')->where('id', '[0-9]+');
    Route::post('manage/{key}/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\ManagersController@archive')->name('chief.back.managers.archive')->where('id', '[0-9]+');

    // Modules
    Route::get('modules', 'Thinktomorrow\Chief\App\Http\Controllers\Back\ModulesController@index')->name('chief.back.modules.index');
    Route::post('modules', 'Thinktomorrow\Chief\App\Http\Controllers\Back\ModulesController@store')->name('chief.back.modules.store');

    /**
     * -----------------------------------------------------------------
     * MANAGER ASSISTANT ROUTES
     * -----------------------------------------------------------------
     */
    Route::post('publish/{key}/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\PublishController@publish')->name('chief.back.managers.publish')->where('id', '[0-9]+');
    Route::post('draft/{key}/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\PublishController@draft')->name('chief.back.managers.draft')->where('id', '[0-9]+');

    Route::get('archive/{key}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Assistants\ArchiveController@index')->name('chief.back.assistants.archive-index');
    Route::post('archive/{key}/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Assistants\ArchiveController@archive')->name('chief.back.assistants.archive')->where('id', '[0-9]+');
    Route::post('unarchive/{key}/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Assistants\ArchiveController@unarchive')->name('chief.back.assistants.unarchive')->where('id', '[0-9]+');

    /**
     * -----------------------------------------------------------------
     * MENU MANAGEMENT
     * -----------------------------------------------------------------
     */
    Route::get('menus', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Menu\MenuController@index')->name('chief.back.menus.index');
    Route::get('menus/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Menu\MenuController@show')->name('chief.back.menus.show');

    Route::get('menuitem', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Menu\MenuItemController@index')->name('chief.back.menuitem.index');
    Route::post('menuitem', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Menu\MenuItemController@store')->name('chief.back.menuitem.store');
    Route::get('menuitem/create/{menutype}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Menu\MenuItemController@create')->name('chief.back.menuitem.create');
    Route::put('menuitem/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Menu\MenuItemController@update')->name('chief.back.menuitem.update');
    Route::get('menuitem/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Menu\MenuItemController@show')->name('chief.back.menuitem.show');
    Route::delete('menuitem/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Menu\MenuItemController@destroy')->name('chief.back.menuitem.destroy');
    Route::get('menuitem/{id}/edit', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Menu\MenuItemController@edit')->name('chief.back.menuitem.edit');

    /**
     * -----------------------------------------------------------------
     * EDITOR API & MEDIA MANAGEMENT (used by editor)
     * -----------------------------------------------------------------
     */
    Route::post('pages/{id}/media', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Media\UploadPagesMediaController@store')->name('pages.media.upload');
    Route::post('modules/{id}/media', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Media\UploadModulesMediaController@store')->name('modules.media.upload');
    Route::post('managers/{key}/{id}/media', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Media\UploadManagersMediaController@store')->name('chief.back.managers.media.upload');
    Route::get('api/internal-links', 'Thinktomorrow\Chief\App\Http\Controllers\Api\InternalLinksController@index')->name('chief.api.internal-links');

    /**
     * -----------------------------------------------------------------
     * USERS & AUTHORIZATION MANAGEMENT
     * -----------------------------------------------------------------
     */
    Route::get('getting-started', 'Thinktomorrow\Chief\App\Http\Controllers\Back\DashboardController@gettingStarted')->name('chief.back.dashboard.getting-started');
    Route::get('logout', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\LoginController@logout')->name('chief.back.logout');

    // USER MANAGEMENT
    Route::get('users', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\UserController@index')->name('chief.back.users.index');
    Route::post('users', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\UserController@store')->name('chief.back.users.store');
    Route::get('users/create', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\UserController@create')->name('chief.back.users.create');
//    Route::delete('users/{user}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\UserController@destroy')->name('chief.back.users.destroy');
    Route::put('users/{user}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\UserController@update')->name('chief.back.users.update');
    Route::get('users/{user}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\UserController@show')->name('chief.back.users.show');
    Route::get('users/{user}/edit', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\UserController@edit')->name('chief.back.users.edit');

    // Prompt for a first / new password
    Route::get('password-prompt', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ChangePasswordController@edit')->name('chief.back.password.edit');
    Route::put('password-prompt', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ChangePasswordController@update')->name('chief.back.password.update');

    // YOUR PROFILE MANAGEMENT
    Route::get('you', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\YouController@edit')->name('chief.back.you.edit');
    Route::put('you', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\YouController@update')->name('chief.back.you.update');

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
     * SETTINGS MANAGEMENT
     * -----------------------------------------------------------------
     */
    Route::put('settings', 'Thinktomorrow\Chief\App\Http\Controllers\Back\System\SettingsController@update')->name('chief.back.settings.update');
    Route::get('settings', 'Thinktomorrow\Chief\App\Http\Controllers\Back\System\SettingsController@edit')->name('chief.back.settings.edit');

    /**
     * -----------------------------------------------------------------
     * AUDIT LOG
     * -----------------------------------------------------------------
     */
    Route::get('audit', 'Thinktomorrow\Chief\App\Http\Controllers\Back\AuditController@index')->name('chief.back.audit.index');
    Route::get('audit/{id}', 'Thinktomorrow\Chief\App\Http\Controllers\Back\AuditController@show')->name('chief.back.audit.show');

    /**
     * -----------------------------------------------------------------
     * SQUANTO TRANSLATION ROUTES
     * -----------------------------------------------------------------
     */
    // Developer access
    Route::get('translations/lines/create', ['as' => 'squanto.lines.create', 'uses' => 'Thinktomorrow\Chief\App\Http\Controllers\Back\Translations\LineController@create']);
    Route::delete('translations/lines/{id}', ['as' => 'squanto.lines.destroy', 'uses' => 'Thinktomorrow\Chief\App\Http\Controllers\Back\Translations\LineController@destroy']);
    Route::get('translations/lines/{id}/edit', ['as' => 'squanto.lines.edit', 'uses' => 'Thinktomorrow\Chief\App\Http\Controllers\Back\Translations\LineController@edit']);
    Route::put('translations/lines/{id}', ['as' => 'squanto.lines.update', 'uses' => 'Thinktomorrow\Chief\App\Http\Controllers\Back\Translations\LineController@update']);
    Route::post('translations/lines', ['as' => 'squanto.lines.store', 'uses' => 'Thinktomorrow\Chief\App\Http\Controllers\Back\Translations\LineController@store']);

    // Client access
    Route::get('translations/{id}/edit', ['as' => 'squanto.edit', 'uses' => 'Thinktomorrow\Chief\App\Http\Controllers\Back\Translations\TranslationController@edit']);
    Route::put('translations/{id}', ['as' => 'squanto.update', 'uses' => 'Thinktomorrow\Chief\App\Http\Controllers\Back\Translations\TranslationController@update']);
    Route::get('translations', ['as' => 'squanto.index', 'uses' => 'Thinktomorrow\Chief\App\Http\Controllers\Back\Translations\TranslationController@index']);
});
