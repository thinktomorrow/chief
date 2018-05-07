<?php

Route::middleware('web')->get('/demo/pages', '\Chief\Demo\Http\Controllers\DemoPageController@index')->name('demo.pages.index');
Route::middleware('web')->get('/demo/pages/{slug}', '\Chief\Demo\Http\Controllers\DemoPageController@show')->name('demo.pages.show');
