<?php

/**
 * -----------------------------------------------------------------
 * TAG MANAGEMENT
 * -----------------------------------------------------------------
 */

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Plugins\Tags\App\Http\TagGroupsController;
use Thinktomorrow\Chief\Plugins\Tags\App\Http\TagsController;

Route::get('tags', [TagsController::class, 'index'])->name('chief.tags.index');
Route::post('tags', [TagsController::class, 'store'])->name('chief.tags.store');
Route::get('tags/create', [TagsController::class, 'create'])->name('chief.tags.create');
Route::delete('tags/{tag}', [TagsController::class, 'delete'])->name('chief.tags.delete');
Route::put('tags/{tag}', [TagsController::class, 'update'])->name('chief.tags.update');
Route::get('tags/{tag}/edit', [TagsController::class, 'edit'])->name('chief.tags.edit');

Route::post('taggroups', [TagGroupsController::class, 'store'])->name('chief.taggroups.store');
Route::get('taggroups/create', [TagGroupsController::class, 'create'])->name('chief.taggroups.create');
Route::delete('taggroups/{taggroup}', [TagGroupsController::class, 'delete'])->name('chief.taggroups.delete');
Route::put('taggroups/{taggroup}', [TagGroupsController::class, 'update'])->name('chief.taggroups.update');
Route::get('taggroups/{taggroup}/edit', [TagGroupsController::class, 'edit'])->name('chief.taggroups.edit');
