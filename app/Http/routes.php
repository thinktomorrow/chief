<?php

Route::get('back/login', 'Auth\AuthController@getLogin');
Route::post('back/login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');

/**
 * -----------------------------------------------------------------
 * ADMIN ROUTES
 * -----------------------------------------------------------------
 */
Route::group(['prefix' => 'back','middleware' =>'auth' ,'namespace' => 'Back'],function(){

    Route::get('contacts',['as' => 'back.contacts.index','uses' => ContactController::class.'@index']);

    // FOR DEVS ONLY!
    Route::get('translations/{slug}/lines/create',['middleware' => 'auth.superadmin', 'as' => 'back.trans.lines.create','uses' => '\Chief\Trans\Controllers\TranslationLineController@create']);
    Route::post('translations/{slug}/lines',['middleware' => 'auth.superadmin', 'as' => 'back.trans.lines.store','uses' => '\Chief\Trans\Controllers\TranslationLineController@store']);
    Route::get('translations/{slug}',['as' => 'back.trans.edit','uses' => '\Chief\Trans\Controllers\TranslationController@edit']);
    Route::put('translations/{group_id}',['as' => 'back.trans.update','uses' => '\Chief\Trans\Controllers\TranslationController@update']);

    Route::get('/',['as' => 'back.dashboard','uses' => DashboardController::class.'@show']);

});

Route::get('/admin',function(){
    return redirect()->route('back.dashboard');
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
Route::group(['prefix' => app(Thinktomorrow\Locale\Locale::class)->set()],function(){

    Route::get('/', ['as' => 'pages.home', 'uses' => function(){
        return view('front.home');
    }]);

    Route::post('contact',['as' => 'contacts.store','middleware' => ['honeypot']  ,'uses' => ContactController::class.'@store']);
    Route::get('search',['as' => 'search.show','uses' => SearchController::class.'@show']);

});
