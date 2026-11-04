<?php

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Plugins\Audit\App\Controllers\HistoryController;

Route::get('audit', [HistoryController::class, 'index'])->name('chief.audit.index');
Route::get('audit/{event}/rich/{piece}/html', [HistoryController::class, 'richHtml'])->name('chief.audit.rich-html');
Route::get('audit/{event}/rich/{piece}/reference', [HistoryController::class, 'richReference'])->name('chief.audit.rich-reference');
