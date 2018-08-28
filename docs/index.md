---
layout: default
title: Install
description: chief is a package based cms built on top of the laravel framework.
navigation_weight: 1
---
## Chief

Chief is a package based cms built on top of the laravel framework.
Chief is solely the back-end(admin panel). You will need to create the front-end yourself.
To install chief we need to install it into another project.
This can be either an existing one or a fresh Laravel 5.6+ project.

Start a new Think Tomorrow project skeleton with the following command
```bash
composer create-project thinktomorrow/project-skeleton <projectname>
```

## Install in existing project

Chief can be installed via composer.
```php
composer require thinktomorrow/chief
```
The package will automatically register its service provider to hook into your application.

Next edit the application exception handler to extend the chief exception handler.
The Chief Exception handler takes care of the admin authentication and authorization.
In the `App\Exceptions\Handler` file extend the class as such:


```File: App\Exceptions\Handler.php```
```php

use Thinktomorrow\Chief\App\Exceptions\Handler as ChiefExceptionHandler;

class Handler extends ChiefExceptionHandler
```

Add the `AuthenticateChiefSession::class` middleware to your `App\Http\Kernel` file. 
You should place these in a `web-chief` middleware group like so:

```File: App\Http\Kernel.php```
```php
protected $middlewareGroups = [
    ...
    'web-chief' => [
            \Thinktomorrow\Chief\App\Http\Middleware\AuthenticateChiefSession::class,
    ],
    ...
]
```

Next in the same file we should add the following entries to the $routeMiddleware array.

```File: App\Http\Kernel.php```
```php
protected $routeMiddleware = [
        'auth.superadmin' => AuthenticateSuperadmin::class,
        'chief-guest' => \Thinktomorrow\Chief\App\Http\Middleware\ChiefRedirectIfAuthenticated::class,
        'chief-validate-invite' => \Thinktomorrow\Chief\App\Http\Middleware\ChiefValidateInvite::class,
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        ...
    ];
```

## Database

Connect a database with your application and make sure you have set the proper database credentials in your `.env` file. 

Next perform the migrate artisan command. This will automatically run the chief migrations as well.
Note that Chief has separate tables for the chief admin users, `chief-users` and `chief_password_resets`. This way there
is no interference with your application user logic.

```php
php artisan migrate
```

Next we need at least one main admin user to login and start managing the admin panel.
This command will create the basic roles and permissions and allows to setup the first admin account:

```php
php artisan chief:admin
```

## Config & Assets

The next step is to publish the chief-assets to our public folder.
If you want to overwrite existing files you can add the `--force` flag here.

```php
php artisan vendor:publish --tag=chief-assets
```

Publish the chief config to `config/thinktomorrow/chief` as this will require you to set some application defaults such as
contact email and application name.
```php
php artisan vendor:publish --tag=chief-config
```
Make sure to set at least the `name` value to your project name as it is used in some of the generator commands. Ideally this should match
the namespace of your `src` folder, if you have any. Make sure to namespace the src folder in your composer.json to match this name.

The following vendor assets should also be published to your application:
```php
// The dimsav translatable package
php artisan vendor:publish --tag=translatable

// The thinktomorrow locale package
php artisan vendor:publish --provider="Thinktomorrow\Locale\LocaleServiceProvider"
```

## Default routes
There is one project related route that is expected by chief and that is: `pages.show`. This
is the route for the detail of a static page. Make sure to add this one. 

For the easiest setup you should also add the `pages.home` route. This will detect the homepage based on the config.

```File: routes\front.php```
```php
Route::get('/', PagesController::class.'@homepage')->name('pages.home');
Route::get('page/{slug}', PagesController::class.'@show')->name('pages.show');
```

Also add a controller file for this front end route.
This one is an example:

```File: App\Http\Controller```
```php
<?php
namespace App\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thinktomorrow\Chief\Pages\Page;

class PagesController extends Controller
{
    public function show($slug)
    {
        if(!$page = Page::findPublishedBySlug($slug)) {
            throw new NotFoundHttpException('No published page found by slug ['.$slug.']');
        }

        // TODO: If slug matches the homepage page, redirect to root to avoid duplicate content
        if($page->isHomepage()) {
            return redirect()->route('pages.home');
        }

        return $page->view();
    }

    public function homepage()
    {
        // Get the page that has the flag 'is_homepage'. Otherwise we take the first singles pages found. If not found, we take the first published page...
        $page = Page::guessHomepage();

        return $page->view();
    }
}
```

To get this route to work it's a good idea to add a view file where we can show a page.

An example of this view file is the following:

```File: resources\views\front\pages\show.blade.php```
```html
@extends('front._layouts.master')

@section('content')

    <!-- hero -->
    <div class="row" style="background: url({{ $page->mediaUrl(\Thinktomorrow\Chief\Media\MediaType::HERO) }}) top right no-repeat;">
        <div class="container">
            <div class="column-7">
                <h1>{{ $page->title }}</h1>
                <div class="editor-content">
                    {!! trans('pages.statics.hero.description') !!}
                </div>
            </div>
        </div>
    </div>

    <section class="container editor-content">
        {!! $page->content !!}
    </section>

    {!! $page->renderChildren() !!}

    <section class="container editor-content">
        {!! $page->hero_title !!}
        {!! $page->hero_description !!}
    </section>

@stop
```

Next to get the front-end to work you should set a homepage id in the chief-settings config file.
This determines what the homepage/landing page will be. Currently this is changed through that config file.
Eventually this will be editable in the admin.

## Multilingual

There are a couple of places where you need to configure the localisation of your application.
At the following files you should change the locales to your desired setup:

- Set the available locales of the application in the `config/translatable.php` file. The values in the `locales` array will be available for the admin to manage.
- Set the frontend locales of the application in the `config/thinktomorrow/locale.php` file. The values in this `locales` array will be the allowed locales for the visitors of your application.
- Set the default and fallback locale in the `config/app.php` file. Keep in mind that this value needs to consist of one of the available locales as set in the `config/translatable.php`.

## Project setup advice
Following adjustments are not automatically enforced but are however recommended in your project.

## MySQL index length
Add following snippet in the AppServiceProvider of your project if you use MySQL older than 5.7.7
ref: https://laravel.com/docs/master/migrations#creating-indexes
`Schema::defaultStringLength(191)`

```File: App\Providers\AppServiceProvider.php```
```php
use Illuminate\Support\Facades\Schema;

/**
 * Bootstrap any application services.
 *
 * @return void
 */
public function boot()
{
    Schema::defaultStringLength(191);
}
```

# FAQ

Q: I get the "Route [login] not defined" error. Help!  
A: Extend our ChiefExceptionHandler in the `app/handler.php` file. This is because the chief admin uses a custom guard and does not rely on the default auth laravel routes.

Q: I get the "Tokenmismatch" error after login into the admin. Help!  
A: This most likely means you have an outdated version of chief. Run 'composer update' to get the latest version.

Q: I get the "Class web-chief does not exist" error. Help!  
A: Add the `AuthenticateChiefSession::class` middleware group to your `App\Http\Kernel.php` file.

Q: I get the 'Expected response code 250 but got code “530”, with message “530 5.7.1 Authentication required ”' error. Help! 
A: Please make sure your mail settings in your .env file are correct.
