<?php

/**
 * -----------------------------------------------------------------
 * ADMIN ROUTES
 * -----------------------------------------------------------------
 */

// Dashboard
use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\App\Http\Controllers\Back\StyleGuideController;
use Thinktomorrow\Chief\App\Http\Controllers\Back\TranslationController;
use Thinktomorrow\Chief\Assets\App\Http\MediaGalleryController;
use Thinktomorrow\Chief\Urls\App\Controllers\RemoveRedirectController;

Route::get('/', 'Thinktomorrow\Chief\App\Http\Controllers\Back\DashboardController@show')->name('chief.back.dashboard');

// Sitemap
Route::get('sitemap', 'Thinktomorrow\Chief\App\Http\Controllers\Back\System\SitemapController@index')->name('chief.back.sitemap.show');
Route::post('sitemap', 'Thinktomorrow\Chief\App\Http\Controllers\Back\System\SitemapController@generate')->name('chief.back.sitemap.generate');

// Urls
Route::delete('remove-redirect/{id}', [RemoveRedirectController::class, 'delete'])->name('chief.back.assistants.url.remove-redirect')->where('id', '[0-9]+');

/**
 * -----------------------------------------------------------------
 * MENU MANAGEMENT
 * -----------------------------------------------------------------
 */
Route::get('menus', [\Thinktomorrow\Chief\Menu\App\Controllers\MenuController::class, 'index'])->name('chief.back.menus.index');
// Route::get('menus/{id}/reorder', [\Thinktomorrow\Chief\Menu\App\Controllers\ReorderMenuController::class, 'index'])->name('chief.back.menus.reorder');
// Route::post('menus/{id}/reorder', [\Thinktomorrow\Chief\Menu\App\Controllers\ReorderMenuController::class, 'update'])->name('chief.back.menus.reorder.update');
Route::post('menus/{id}/menuitem', [\Thinktomorrow\Chief\Menu\App\Controllers\MenuItemController::class, 'store'])->name('chief.back.menuitem.store');
Route::get('menus/{id}/menuitem/create', [\Thinktomorrow\Chief\Menu\App\Controllers\MenuItemController::class, 'create'])->name('chief.back.menuitem.create');
Route::get('menus/{type}/{id?}', [\Thinktomorrow\Chief\Menu\App\Controllers\MenuController::class, 'show'])->name('chief.back.menus.show');

Route::put('menuitem/{id}', [\Thinktomorrow\Chief\Menu\App\Controllers\MenuItemController::class, 'update'])->name('chief.back.menuitem.update');
Route::get('menuitem/{id}', [\Thinktomorrow\Chief\Menu\App\Controllers\MenuItemController::class, 'show'])->name('chief.back.menuitem.show');
Route::delete('menuitem/{id}', [\Thinktomorrow\Chief\Menu\App\Controllers\MenuItemController::class, 'destroy'])->name('chief.back.menuitem.destroy');
Route::get('menuitem/{id}/edit', [\Thinktomorrow\Chief\Menu\App\Controllers\MenuItemController::class, 'edit'])->name('chief.back.menuitem.edit');

/**
 * -----------------------------------------------------------------
 * EDITOR API & MEDIA MANAGEMENT
 * -----------------------------------------------------------------
 */
Route::get('api/internal-links', 'Thinktomorrow\Chief\App\Http\Controllers\Api\InternalLinksController@index')->name('chief.api.internal-links');
Route::get('mediagallery', [MediaGalleryController::class, 'index'])->name('chief.mediagallery.index');

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
Route::get('translations/{id}/edit', [TranslationController::class, 'edit'])->name('squanto.edit');
Route::put('translations/{id}', [TranslationController::class, 'update'])->name('squanto.update');
Route::get('translations', [TranslationController::class, 'index'])->name('squanto.index');

/**
 * -----------------------------------------------------------------
 * STYLE GUIDE ROUTES
 * -----------------------------------------------------------------
 */
Route::get('/style-guide', [StyleGuideController::class, 'show'])->name('chief.back.style-guide');
