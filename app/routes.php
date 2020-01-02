<?php

/**
 * -----------------------------------------------------------------
 * SPIRIT ROUTES
 * -----------------------------------------------------------------
 */
Route::get('spirit/{section?}/{item?}', ['as' => 'spirit.index', 'uses' => function ($section = null, $item = null) {
    if ($section && $item && view()->exists('chief::spirit.'.$section.'.'.$item)) {
        return view('chief::spirit.'.$section.'.'.$item);
    }

    return view('chief::spirit.home');
}])->middleware('web');

/**
 * -----------------------------------------------------------------
 * NON-AUTHENTICATED ADMIN ROUTES
 * -----------------------------------------------------------------
 */
Route::group(['prefix' => config('thinktomorrow.chief.route.prefix', 'admin'), 'middleware' => ['web']], function () {

});

/**
 * -----------------------------------------------------------------
 * ADMIN ROUTES
 * -----------------------------------------------------------------
 */
Route::group(['prefix' => config('thinktomorrow.chief.route.prefix', 'admin'), 'middleware' => ['web', 'web-chief', 'auth:chief']], function () {


});
