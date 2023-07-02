<?php

/**
 * -----------------------------------------------------------------
 * NON-AUTHENTICATED ADMIN ROUTES
 * -----------------------------------------------------------------
 */
Route::get('login', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\LoginController@showLoginForm')
    ->name('chief.back.login')
    ->middleware('web');
Route::post('login', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\LoginController@login')
    ->name('chief.back.login.store')
    ->middleware('web');

// Password Reset Routes...
Route::get('password/reset', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')
    ->name('chief.back.password.request')
    ->middleware('web');
Route::post('password/email', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')
    ->name('chief.back.password.email')
    ->middleware('web');
Route::get('password/reset/{token}', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ResetPasswordController@showResetForm')
    ->name('chief.back.password.reset')
    ->middleware('web');
Route::post('password/reset', 'Thinktomorrow\Chief\App\Http\Controllers\Auth\ResetPasswordController@reset')
    ->name('chief.back.password.reset.store')
    ->middleware('web');

// Invitation routes...
Route::get('invite/expired', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\InviteController@expired')
    ->name('invite.expired')
    ->middleware('web');
Route::get('invite/{token}/accept', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\InviteController@accept')
    ->name('invite.accept')
    ->middleware('web');
Route::get('invite/{token}/deny', 'Thinktomorrow\Chief\App\Http\Controllers\Back\Users\InviteController@deny')
    ->name('invite.deny')
    ->middleware('web');
