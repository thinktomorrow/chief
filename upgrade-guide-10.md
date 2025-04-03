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
```

Add following snippet to the redactor options in your project editor file (
resources/views/vendor/chief/editors/redactor/editor.blade.php) to allow sync between redactor content and livewire
wire:model.

```php
customOptions['callbacks'] = {
    changed: function(e) {
        let content = this.source.getCode();
        el.value = content;
        el.dispatchEvent(new Event('input', { bubbles: true }));
    },
};

window.Redactor(el, customOptions);
```

### Replace Field Tag not-on-create with not-on-model-create

To exclude fields to show up on a model create page, use the `not-on-model-create` tag on the field.
The former `not-on-create` tag is used to exclude on both models and fragments.

### Fragments

#### View paths

Previously, fragments had a private `$baseViewPath` and `$viewPath` variable to set the front-end view paths. The
back-end view path was defined by a function named `renderAdminFragments`, which returned a view.

Now, they are both defined as protected class variables, like so:

```php
  protected $adminViewPath = 'back.fragments.text';
  protected $viewPath = 'theme::fragments.text';
```

### Chief config

The locales config is no longer used.
Add the sites config to your config file. This is a new config value used to determine the sites for your project.
You can reference the config file in the chief package as a starting point.

Also replace `config('chief.locales')` by `\Thinktomorrow\Chief\Sites\Locales\ChiefLocales::locales()::locales()`.

### Fragments render in views

On the front-end, replace the @fragments with:

```
@foreach (getFragments() as $fragment)
    {!! $fragment->with(['page' => $model])->render() !!}
@endforeach
```

