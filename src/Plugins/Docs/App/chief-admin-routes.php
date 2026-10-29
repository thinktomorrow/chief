<?php

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Plugins\Docs\App\Http\DocsController;
use Thinktomorrow\Chief\Plugins\Docs\App\Prototype\GuidesPrototypeController;

Route::get('docs', [DocsController::class, 'index'])->name('chief.docs.index');
Route::get('docs/{page}', [DocsController::class, 'show'])->name('chief.docs.show');

// PROTOTYPE (wayfinder ticket 08): throwaway guides UI variants, local only.
if (app()->isLocal()) {
    Route::get('docs-prototype-asset/{name}', [GuidesPrototypeController::class, 'asset']);
    Route::get('docs-prototype/{id?}', [GuidesPrototypeController::class, 'show'])->where('id', '.*')->name('chief.docs.prototype');
}
