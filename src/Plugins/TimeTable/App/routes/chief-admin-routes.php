<?php

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Plugins\TimeTable\App\Http\DateController;
use Thinktomorrow\Chief\Plugins\TimeTable\App\Http\DayController;
use Thinktomorrow\Chief\Plugins\TimeTable\App\Http\TimeTableController;

Route::post('timetable_dates', [DateController::class, 'store'])->name('chief.timetable_dates.store');
Route::get('timetable_dates/{timetable_id}/create', [DateController::class, 'create'])->name('chief.timetable_dates.create');
Route::delete('timetable_dates/{timetable_date}', [DateController::class, 'delete'])->name('chief.timetable_dates.delete');
Route::put('timetable_dates/{timetable_date}', [DateController::class, 'update'])->name('chief.timetable_dates.update');
Route::get('timetable_dates/{timetable_id}/{timetable_date}/edit', [DateController::class, 'edit'])->name('chief.timetable_dates.edit');

Route::put('timetable_days/{timetable_day}', [DayController::class, 'update'])->name('chief.timetable_days.update');
Route::get('timetable_days/{timetable_day}/edit', [DayController::class, 'edit'])->name('chief.timetable_days.edit');

Route::get('timetables', [TimeTableController::class, 'index'])->name('chief.timetables.index');
Route::post('timetables', [TimeTableController::class, 'store'])->name('chief.timetables.store');
Route::get('timetables/create', [TimeTableController::class, 'create'])->name('chief.timetables.create');
Route::delete('timetables/{timetable}', [TimeTableController::class, 'delete'])->name('chief.timetables.delete');
Route::put('timetables/{timetable}', [TimeTableController::class, 'update'])->name('chief.timetables.update');
Route::get('timetables/{timetable}/edit', [TimeTableController::class, 'edit'])->name('chief.timetables.edit');
