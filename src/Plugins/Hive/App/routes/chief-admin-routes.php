<?php

/**
 * -----------------------------------------------------------------
 * HIVE API ROUTES
 * -----------------------------------------------------------------
 */

use Illuminate\Support\Facades\Route;

Route::post('hive/suggest', [\Thinktomorrow\Chief\Plugins\Hive\App\Controllers\HiveController::class, 'suggest'])->name('chief.hive.suggest');
