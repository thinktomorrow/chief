<?php

Route::middleware('web')->get('/demo/pages', '\Thinktomorrow\Chief\Demo\Http\Controllers\DemoPageController@index')->name('demo.pages.index');
Route::middleware('web')->get('/demo/pages/{slug}', '\Thinktomorrow\Chief\Demo\Http\Controllers\DemoPageController@show')->name('demo.pages.show');
