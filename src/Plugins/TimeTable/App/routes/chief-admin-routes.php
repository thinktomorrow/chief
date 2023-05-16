<?php

use Illuminate\Support\Facades\Route;

Route::post('timetable_dates', [\Thinktomorrow\Chief\Plugins\TimeTable\App\Http\DateController::class,'store'])->name('chief.timetable_dates.store');
Route::get('timetable_dates/{timetable_id}/create', [\Thinktomorrow\Chief\Plugins\TimeTable\App\Http\DateController::class,'create'])->name('chief.timetable_dates.create');
Route::delete('timetable_dates/{timetable_date}', [\Thinktomorrow\Chief\Plugins\TimeTable\App\Http\DateController::class,'delete'])->name('chief.timetable_dates.delete');
Route::put('timetable_dates/{timetable_date}', [\Thinktomorrow\Chief\Plugins\TimeTable\App\Http\DateController::class,'update'])->name('chief.timetable_dates.update');
Route::get('timetable_dates/{timetable_id}/{timetable_date}/edit', [\Thinktomorrow\Chief\Plugins\TimeTable\App\Http\DateController::class,'edit'])->name('chief.timetable_dates.edit');

Route::put('timetable_days/{timetable_day}', [\Thinktomorrow\Chief\Plugins\TimeTable\App\Http\DayController::class,'update'])->name('chief.timetable_days.update');
Route::get('timetable_days/{timetable_day}/edit', [\Thinktomorrow\Chief\Plugins\TimeTable\App\Http\DayController::class,'edit'])->name('chief.timetable_days.edit');

Route::get('timetables', [\Thinktomorrow\Chief\Plugins\TimeTable\App\Http\TimeTableController::class,'index'])->name('chief.timetables.index');
Route::post('timetables', [\Thinktomorrow\Chief\Plugins\TimeTable\App\Http\TimeTableController::class,'store'])->name('chief.timetables.store');
Route::get('timetables/create', [\Thinktomorrow\Chief\Plugins\TimeTable\App\Http\TimeTableController::class,'create'])->name('chief.timetables.create');
Route::delete('timetables/{timetable}', [\Thinktomorrow\Chief\Plugins\TimeTable\App\Http\TimeTableController::class,'delete'])->name('chief.timetables.delete');
Route::put('timetables/{timetable}', [\Thinktomorrow\Chief\Plugins\TimeTable\App\Http\TimeTableController::class,'update'])->name('chief.timetables.update');
Route::get('timetables/{timetable}/edit', [\Thinktomorrow\Chief\Plugins\TimeTable\App\Http\TimeTableController::class,'edit'])->name('chief.timetables.edit');
