# Install Chief

First create chief-skeleton project or a blank laravel project
```php 
composer require thinktomorrow/chief
``` 

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
- Schema::defaultStringLength(191);

    