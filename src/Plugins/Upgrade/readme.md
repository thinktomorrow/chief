### Upgrade from 0.9 to 0.10

You can run the upgrade command to facilitate the upgrade process.

- First temporarily comment the existing service providers of your project to avoid conflicts during installing chief
  0.10
- Next install the chief 0.10 package

```bash 
composer require "chief/chief:^0.10"
```

- Next, add the `Thinktomorrow\Chief\Plugins\Upgrade\UpgradeServiceProvider::class` to the list of project service
  providers in the `bootstrap/providers.php` (Laravel 11+) or `config/app.php` (older Laravel versions) file.
- Next, run the upgrade command

```bash
php artisan chief:upgrade-from-9-to-10
```

- Make sure that all your resources have a sites column in the database. You can add it manually by creating and running
  the following migration:

```php 
Schema::table('pages', function (Blueprint $table) {
    $table->json('sites')->nullable();
});
```

### Views

use getFragments() function instead of @fragments.
This directive does not make use of the component rendering of fragments.
Best to loop the fragments in the view like:

```php 
@foreach(getFragments() as $fragment) {{ $fragment->render() }} @endforeach
```
