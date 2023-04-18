<?php

use Illuminate\Support\Facades\Route;

Route::post('weektable_dates', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\DateController::class,'store'])->name('chief.weektable_dates.store');
Route::get('weektable_dates/create', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\DateController::class,'create'])->name('chief.weektable_dates.create');
Route::delete('weektable_dates/{weektable_date}', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\DateController::class,'delete'])->name('chief.weektable_dates.delete');
Route::put('weektable_dates/{weektable_date}', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\DateController::class,'update'])->name('chief.weektable_dates.update');
Route::get('weektable_dates/{weektable_date}/edit', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\DateController::class,'edit'])->name('chief.weektable_dates.edit');

Route::post('weektable_days', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\DayController::class,'store'])->name('chief.weektable_days.store');
Route::get('weektable_days/create', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\DayController::class,'create'])->name('chief.weektable_days.create');
Route::delete('weektable_days/{weektable_day}', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\DayController::class,'delete'])->name('chief.weektable_days.delete');
Route::put('weektable_days/{weektable_day}', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\DayController::class,'update'])->name('chief.weektable_days.update');
Route::get('weektable_days/{weektable_day}/edit', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\DayController::class,'edit'])->name('chief.weektable_days.edit');

Route::get('weektables', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\WeekTableController::class,'index'])->name('chief.weektables.index');
Route::post('weektables', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\WeekTableController::class,'store'])->name('chief.weektables.store');
Route::get('weektables/create', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\WeekTableController::class,'create'])->name('chief.weektables.create');
Route::delete('weektables/{weektable}', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\WeekTableController::class,'delete'])->name('chief.weektables.delete');
Route::put('weektables/{weektable}', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\WeekTableController::class,'update'])->name('chief.weektables.update');
Route::get('weektables/{weektable}/edit', [\Thinktomorrow\Chief\Plugins\WeekTable\Admin\Http\WeekTableController::class,'edit'])->name('chief.weektables.edit');
