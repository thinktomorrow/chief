<?php

use Illuminate\Support\Facades\Route;

Route::get('docs', [\Thinktomorrow\Chief\Plugins\Docs\App\Http\DocsController::class, 'index'])->name('chief.docs.index');
Route::get('docs/{page}', [\Thinktomorrow\Chief\Plugins\Docs\App\Http\DocsController::class, 'show'])->name('chief.docs.show');
