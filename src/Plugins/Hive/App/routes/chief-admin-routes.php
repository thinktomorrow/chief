<?php

/**
 * -----------------------------------------------------------------
 * HIVE API ROUTES
 * -----------------------------------------------------------------
 */

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Plugins\Hive\App\Controllers\HiveController;

Route::post('hive/suggest', [HiveController::class, 'suggest'])->name('chief.hive.suggest');
