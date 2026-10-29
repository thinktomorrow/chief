<?php

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Plugins\Docs\App\Http\DocsController;
use Thinktomorrow\Chief\Plugins\Docs\Media\AssetType;

Route::get('docs', [DocsController::class, 'index'])->name('chief.docs.index');
Route::get('docs/{assetId}', [DocsController::class, 'asset'])->name('chief.docs.asset')->where('assetId', AssetType::routePattern());
Route::get('docs/{guideId}', [DocsController::class, 'show'])->name('chief.docs.show')->where('guideId', '.+');
