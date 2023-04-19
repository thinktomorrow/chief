<?php

use Illuminate\Support\Facades\Route;

Route::post('timetable_dates', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\DateController::class,'store'])->name('chief.timetable_dates.store');
Route::get('timetable_dates/create', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\DateController::class,'create'])->name('chief.timetable_dates.create');
Route::delete('timetable_dates/{timetable_date}', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\DateController::class,'delete'])->name('chief.timetable_dates.delete');
Route::put('timetable_dates/{timetable_date}', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\DateController::class,'update'])->name('chief.timetable_dates.update');
Route::get('timetable_dates/{timetable_date}/edit', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\DateController::class,'edit'])->name('chief.timetable_dates.edit');
//
//Route::post('timetable_days', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\DayController::class,'store'])->name('chief.timetable_days.store');
//Route::get('timetable_days/create', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\DayController::class,'create'])->name('chief.timetable_days.create');
//Route::delete('timetable_days/{timetable_day}', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\DayController::class,'delete'])->name('chief.timetable_days.delete');
//Route::put('timetable_days/{timetable_day}', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\DayController::class,'update'])->name('chief.timetable_days.update');
//Route::get('timetable_days/{timetable_day}/edit', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\DayController::class,'edit'])->name('chief.timetable_days.edit');

Route::get('timetables', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\TimeTableController::class,'index'])->name('chief.timetables.index');
Route::post('timetables', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\TimeTableController::class,'store'])->name('chief.timetables.store');
Route::get('timetables/create', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\TimeTableController::class,'create'])->name('chief.timetables.create');
Route::delete('timetables/{timetable}', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\TimeTableController::class,'delete'])->name('chief.timetables.delete');
Route::put('timetables/{timetable}', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\TimeTableController::class,'update'])->name('chief.timetables.update');
Route::get('timetables/{timetable}/edit', [\Thinktomorrow\Chief\Plugins\TimeTable\Admin\Http\TimeTableController::class,'edit'])->name('chief.timetables.edit');
