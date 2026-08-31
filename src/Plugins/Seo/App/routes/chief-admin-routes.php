<?php

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Plugins\Seo\App\Controllers\SeoController;

Route::get('seo', [SeoController::class, 'index'])->name('chief.seo.index');
Route::get('seo/assets', [SeoController::class, 'assetsIndex'])->name('chief.seo.assets.index');
