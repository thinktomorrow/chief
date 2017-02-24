<?php

/**
 * -----------------------------------------------------------------
 * ADMIN ROUTES
 * -----------------------------------------------------------------
 */
Route::group(['prefix' => 'admin','middleware' =>'auth' ,'namespace' => 'Back'],function(){

    // FOR DEVS ONLY!
    Route::get('translations/{slug}/lines/create',['middleware' => 'auth.superadmin', 'as' => 'back.trans.lines.create','uses' => '\Chief\Trans\Controllers\TranslationLineController@create']);
    Route::post('translations/{slug}/lines',['middleware' => 'auth.superadmin', 'as' => 'back.trans.lines.store','uses' => '\Chief\Trans\Controllers\TranslationLineController@store']);
    Route::get('translations/{slug}',['as' => 'back.trans.edit','uses' => '\Chief\Trans\Controllers\TranslationController@edit']);
    Route::put('translations/{group_id}',['as' => 'back.trans.update','uses' => '\Chief\Trans\Controllers\TranslationController@update']);

    Route::get('/',['as' => 'back.dashboard','uses' => DashboardController::class.'@show']);

});

/**
 * -----------------------------------------------------------------
 * PUBLIC LANGUAGE SWITCHER
 * -----------------------------------------------------------------
 */
Route::get('lang',['as' => 'lang.switch','uses' => LanguageSwitcher::class.'@store']);

/**
 * -----------------------------------------------------------------
 * PUBLIC TRANSLATABLE ROUTES
 * -----------------------------------------------------------------
 */
Route::group(['prefix' => Locale::set(),'namespace' => 'Front'],function(){

    Route::get('/', ['as' => 'pages.home', 'uses' => function(){
        return view('front.home');
    }]);

});

// SETUP ROUTES
Route::get('/', function () {
    return view('setup.welcome');
});

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
