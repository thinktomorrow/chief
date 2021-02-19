<?php

Route::get('dummy-route', function () {
})->name('dummy.route');
Route::post('dummy-favorite/{assistant}/{method}/{manager}/{model}', [\Thinktomorrow\Chief\App\Http\Controllers\Back\Assistants\AssistantController::class,'update'])->name('dummy.favorite');
