<?php

use Thinktomorrow\Chief\App\Http\Controllers\Back\Assistants\AssistantController;

Route::get('dummy-route', function () {})->name('dummy.route');
Route::post('dummy-favorite/{assistant}/{method}/{manager}/{model}', [AssistantController::class, 'update'])->name('dummy.favorite');
