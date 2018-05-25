# Concept

Chief is a package based cms built on top of the laravel framework.
Chief is solely the back-end(or admin panel) of a project. You will need to create the front-end yourself.

# Install Chief

To install chief we need to install it into another project.
This can be either an existing one or a new Laravel 5.6 project.

We require the package using composer.
```php
composer require thinktomorrow/chief
```

Next we need the laravel exception handler to extend the chief exception handler like so:

App\Exceptions\Handler
```php
use ThinkTomorrow\Chief\Exceptions\Handler as ChiefExceptionHandler

class Handler extends ChiefExceptionHandler
{
```

To get access to the back-end assets we publish the chief-assets to our public folder.

```php
php artisan vendor:publish --tag=chief-assets
```

If you want to overwrite existing files you can add the --force flag here.

Add the following middleware group to the Http\Kernel file:

```php
protected $middlewareGroups = [
    //...
    'web-chief' => [
                AuthenticateChiefSession::class,
    ],
    //...
]
```

Add the bugsnag service provider to your app.php service providers:

```php
'providers' => [
    // ...
    Bugsnag\BugsnagLaravel\BugsnagServiceProvider::class,
    // ...
],
```

# Multilingual

We need to remove the subarray in the Config/translatable.php file and the locales array.
The remaining locales will be the available locales fo the app.

```php
    'locales' => [
        'nl',
        'en'
    ],
```

The locale that has been defined in the config/app.php file(which is the default locale) needs to match one of the locales in the translatable locales array.

Next in the config/thinktomorrow/locale.php the locales define the possible locale values for the front end.


# Changing Chief model behaviour

To change the model behaviour for chief models you can extend the models in your application.

# Local development

For local development of chief we need another project to include the Chief package into since a package does not contain the whole laravel framework.
To set up the chief package for local development we link our local chief folder as a repository in the composer.json file.

```php
“repositories”:[
       {
           “url”:“/Users/bencavens/Code/packages/chief”,
           “type”:“path”,
           “options”:{
               “symlinks”:true
           }
       }
   ],
```

The url property needs to be the full path to the local version of the chief package.
To let composer know we want chief to link to the local version we need to run a ```composer update``` or ```composer dumpautoload```

# Database tables

Chief uses a fair amount of database tables. To make it easier in use and since nothing would work without it, running ```php artisan migrate```
will export all the necessary migrations automaticaly.

# Project setup advice
Following adjustments are not automatically enforced but are however recommended in your project.

## MySQL index length
Add following snippet in the AppServiceProvider of your project if you use MySQL older than 5.7.7
ref: https://laravel.com/docs/master/migrations#creating-indexes
`Schema::defaultStringLength(191)`

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

## Required setup steps of your project after installment
- include ChiefServiceProvider


- Extend in your project the Chief Exception handler.
- extend the Http kernel of chief
- publish all the config files:
    - translatable for providing the model translations
    - locale for allowing frontend translation
    - chief-assets (php artisan vendor:publish --tag=chief-assets)
- Schema::defaultStringLength(191);
