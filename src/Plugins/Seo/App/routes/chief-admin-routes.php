<?php

use Illuminate\Support\Facades\Route;

Route::get('seo', [\Thinktomorrow\Chief\Plugins\Seo\App\Controllers\SeoController::class, 'index'])->name('chief.seo.index');
Route::get('seo/assets', [\Thinktomorrow\Chief\Plugins\Seo\App\Controllers\SeoController::class, 'assetsIndex'])->name('chief.seo.assets.index');
