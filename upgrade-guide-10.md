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

- Make sure that all your resources have a locales column in the database. You can add it manually by creating and
  running
  the following migration:

```php 
Schema::table('pages', function (Blueprint $table) {
    $table->json('locales')->nullable();
});
```

### Views

use getFragments() function instead of @fragments.
This directive does not make use of the component rendering of fragments.
Best to loop the fragments in the view like:

```php 
@foreach(getFragments() as $fragment) {{ $fragment->render() }} @endforeach
```

Default localized key is now `:name.:locale` instead of `trans.:locale.:name`. For existing projects, you
can set the default key back by adding the following to your AppServiceProvider file:

```php
Thinktomorrow\Chief\Forms\Fields\FieldName\FieldName::setDefaultTemplate('trans.:locale.:name');
```

### Redactor

Add next snippet to your `vendor/chief/editors/redactor/editor.blade.php` file to make the redactor work in livewire
dialogs.

```php
// Load redactor in livewire dialogs
document.addEventListener('form-dialog-opened', (event) => {

    // Next tick my friend... next tick
    setTimeout(() => {
        const dialogEl = event.detail.componentId ? document.querySelector(`[wire\\:id="${event.detail.componentId}"]`) : document;
        loadRedactorInstances(dialogEl);
    }, 0);
});

// Add following snippet to the redactor options in your project editor file (resources/views/vendor/chief/editors/redactor/editor.blade.php) to allow sync between redactor content and livewire wire:model.
customOptions['callbacks'] = {
    changed: function(e) {
        let content = this.source.getCode();
        el.value = content;
        el.dispatchEvent(new Event('input', { bubbles: true }));
    },
};

window.Redactor(el, customOptions);
```
