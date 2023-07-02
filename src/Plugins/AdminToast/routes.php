<?php

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Plugins\AdminToast\ToastController;

// Toggles frontend preview mode via frontend toast widget
Route::get('admin/toast/toggle', [ToastController::class, 'toggle'])
    ->middleware(['web-chief', 'auth:chief'])
    ->name('chief.toast.toggle');

// Retrieve the toast html - which is fetched async (to avoid loading chief auth logic on every site visit).
Route::get('admin/toast', [ToastController::class, 'get'])
    ->middleware(['web'])
    ->name('chief.toast.get');
