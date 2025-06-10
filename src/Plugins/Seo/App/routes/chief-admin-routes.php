<?php

use Illuminate\Support\Facades\Route;

Route::get('seo', [\Thinktomorrow\Chief\Plugins\Seo\App\Controllers\SeoController::class, 'index'])->name('chief.seo.index');
Route::get('seo/alt', [\Thinktomorrow\Chief\Plugins\Seo\App\Controllers\SeoController::class, 'altIndex'])->name('chief.seo.alt.index');
