<?php

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Plugins\Audit\App\Controllers\HistoryController;

Route::get('audit', [HistoryController::class, 'index'])->name('chief.audit.index');
