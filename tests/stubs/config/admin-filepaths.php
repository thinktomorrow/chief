<?php

Route::get('dummy-route', function(){})->name('dummy.route');
Route::post('dummy-favorite/{key}/{id}/{assistant}', [\Thinktomorrow\Chief\App\Http\Controllers\Back\Assistants\AssistantController::class,'favorize'])->name('dummy.favorite');
