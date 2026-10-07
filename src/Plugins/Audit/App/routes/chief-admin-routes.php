<?php

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Plugins\Audit\App\Controllers\HistoryController;

Route::get('audit', [HistoryController::class, 'index'])->name('chief.audit.index');
Route::get('audit/{event}/details', [HistoryController::class, 'eventDetails'])->name('chief.audit.event-details');
Route::get('audit/{event}/rich', [HistoryController::class, 'richData'])->name('chief.audit.rich-data');
Route::get('audit/{event}/rich/{piece}/html', [HistoryController::class, 'richHtml'])->name('chief.audit.rich-html');
Route::get('audit/{event}/rich/{piece}/reference', [HistoryController::class, 'richReference'])->name('chief.audit.rich-reference');
Route::get('audit/{event}/{model}', [HistoryController::class, 'details'])->name('chief.audit.details');
