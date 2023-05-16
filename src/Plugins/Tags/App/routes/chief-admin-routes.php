<?php

/**
 * -----------------------------------------------------------------
 * TAG MANAGEMENT
 * -----------------------------------------------------------------
 */

use Illuminate\Support\Facades\Route;

Route::get('tags', [\Thinktomorrow\Chief\Plugins\Tags\App\Http\TagsController::class,'index'])->name('chief.tags.index');
Route::post('tags', [\Thinktomorrow\Chief\Plugins\Tags\App\Http\TagsController::class,'store'])->name('chief.tags.store');
Route::get('tags/create', [\Thinktomorrow\Chief\Plugins\Tags\App\Http\TagsController::class,'create'])->name('chief.tags.create');
Route::delete('tags/{tag}', [\Thinktomorrow\Chief\Plugins\Tags\App\Http\TagsController::class,'delete'])->name('chief.tags.delete');
Route::put('tags/{tag}', [\Thinktomorrow\Chief\Plugins\Tags\App\Http\TagsController::class,'update'])->name('chief.tags.update');
Route::get('tags/{tag}/edit', [\Thinktomorrow\Chief\Plugins\Tags\App\Http\TagsController::class,'edit'])->name('chief.tags.edit');

Route::post('taggroups', [\Thinktomorrow\Chief\Plugins\Tags\App\Http\TagGroupsController::class,'store'])->name('chief.taggroups.store');
Route::get('taggroups/create', [\Thinktomorrow\Chief\Plugins\Tags\App\Http\TagGroupsController::class,'create'])->name('chief.taggroups.create');
Route::delete('taggroups/{taggroup}', [\Thinktomorrow\Chief\Plugins\Tags\App\Http\TagGroupsController::class,'delete'])->name('chief.taggroups.delete');
Route::put('taggroups/{taggroup}', [\Thinktomorrow\Chief\Plugins\Tags\App\Http\TagGroupsController::class,'update'])->name('chief.taggroups.update');
Route::get('taggroups/{taggroup}/edit', [\Thinktomorrow\Chief\Plugins\Tags\App\Http\TagGroupsController::class,'edit'])->name('chief.taggroups.edit');
