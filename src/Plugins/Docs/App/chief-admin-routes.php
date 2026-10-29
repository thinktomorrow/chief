<?php

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Plugins\Docs\App\Http\DocsController;

Route::get('docs', [DocsController::class, 'index'])->name('chief.docs.index');
Route::get('docs/{guideId}', [DocsController::class, 'show'])->name('chief.docs.show')->where('guideId', '.+');
