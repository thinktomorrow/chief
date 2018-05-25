# Chief

Chief is a package based cms built on top of the laravel framework.
Chief is solely the back-end(or admin panel) of a project. You will need to create the front-end yourself.
To install chief we need to install it into another project.
This can be either an existing one or a new Laravel 5.6 project.

## Installment

Chief can be installed via composer. 
```php
composer require thinktomorrow/chief
```
The package will automatically register its service provider to hook into your application.

Next edit the application exception handler to extend the chief exception handler.
The Chief Exception handler takes care of the admin authentication and authorization.
In the `App\Exceptions\Handler` file extend the class as such:

```php
use Thinktomorrow\Chief\App\Exceptions\Handler as ChiefExceptionHandler;

class Handler extends ChiefExceptionHandler
```

Create a database for your application and perform the migrate artisan command. This will automatically run the chief migrations as well.
Note that Chief has separate tables for the chief admin users, `chief-users` and `chief_password_resets`. This way there
is no interference with your application user logic.

```php
php artisan migrate
```

Ok so now you need at least one main admin user to login and start managing the admin.
This command will create the basic roles and permissions and allows to setup the first admin account:

```php
php artisan chief:create-admin
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
