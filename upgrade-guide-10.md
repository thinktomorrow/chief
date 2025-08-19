### Upgrade from 0.9 to 0.10

You can run the upgrade command to facilitate the upgrade process.

- First temporarily comment the existing service providers of your project to avoid conflicts during installing chief
  0.10
- Set the chief.sites config so the upgrade script can run with the proper sites.
- Next install the chief 0.10 package

## Run upgrade command

First, add the sites config to your chief config file. This replaces the chief.locales config.

```bash
composer require "chief/chief:^0.10"
```

- Next, add the `Thinktomorrow\Chief\Plugins\Upgrade\UpgradeServiceProvider::class` to the list of project service
  providers in the `bootstrap/providers.php` (Laravel 11+) or `config/app.php` (older Laravel versions) file.
- Next, run the upgrade command

```bash
php artisan chief:upgrade-from-9-to-10
```

## Migrations

- Make sure that all your resources have an allowed_sites column in the database. You can add it manually by creating
  and
  running
  the following migration:

```php
Schema::table('pages', function (Blueprint $table) {
    $table->json('allowed_sites')->nullable();
});
```

### Chief Nav

Move your project `nav-project` file to `resources/views/vendor/chief/templates/page/nav/nav-project.blade.php`

### Redactor

Add next snippet to your `vendor/chief/editors/redactor/editor.blade.php` file to make the redactor work in livewire
dialogs.

```php
// Load redactor in livewire dialogs
document.addEventListener('form-dialog-opened', (event) => {
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

### Redactor backend styles

redactor-styles.css content needs to be updated. This file is located in the project and is responsible for the backend
build of redactor.
Usually found at: `resources/assets/back/css/redactor-styles.css`.

Import Tailwind CSS with a prefix so this doesn't override the Chief CSS variables.
This allows us to still use the project specific theme to style the redactor editor in Chief.

Here's an example:

```css 

@import 'tailwindcss' prefix(rs);
@import './../../css/general/theme.css';

/* Unset any Chief classes that could clash with project specified classes */
.redactor-styles {
    .btn,
    .btn-primary {
        all: unset;
    }
}

/* Define all project specific classes */
/* This selector is more unique than the above 'unset' classes to guarantee they are not overridden */
.redactor-box .redactor-styles {
    /* ---------- Prose styling ---------- */
    @apply rs:font-body;

    /* ---------- Custom classes ---------- */

    .btn {
        @apply rs:inline-block rs:rounded-lg rs:px-6 rs:py-3 rs:font-medium rs:shadow;
    }

    .btn-primary {
        @apply rs:bg-primary-500 rs:text-white;
    }
}

```

### Replace Field Tag not-on-create with not-on-model-create

To exclude fields to show up on a model create page, use the `not-on-model-create` tag on the field.
The former `not-on-create` tag is used to exclude on both models and fragments.

### Fragments & Fields

#### View paths

Previously, fragments had a private `$baseViewPath` and `$viewPath` variable to set the front-end view paths. The
back-end view path was defined by a function named `renderAdminFragments`, which returned a view.

Now, they are both defined as protected class variables, like so:

```php
  protected $adminViewPath = 'back.fragments.text';
  protected $viewPath = 'theme::fragments.text';
```

#### Views

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

- url() method on visitable models now only gets online urls. Use `rawUrl` to fetch offline urls as well.
- `online` scope has changed. Use `online` scope to get models that: are allowed on given site, are published and have
  online url for given site.
- Former `online` scope is now changed to `published`.
- Fetch only the active locales for your language switcher via: `ChiefSites::activeLocales()`.

### Chief config

The locales config is no longer used.
Add the sites config to your config file. This is a new config value used to determine the sites for your project.
You can reference the config file in the chief package as a starting point.

Also replace `config('chief.locales')` by `\Thinktomorrow\Chief\Sites\ChiefSites::locales()`.

### Menu

The `chief.menus` configuration now uses a simple associative array: each key is a menu type and each value is its
label.
Please change the format in your `config/chief.php` file accordingly.

```php
return [
    'menus' => [
        'main' => 'Hoofdnavigatie',
        'footer' => 'Footer links',
        'legal' => 'Juridische links',
        // Add more menu types as needed
    ],
];
```

For new projects, you need to add the default menus to the database.
You can use the artisan command:

```bash
php artisan chief:default-menus
```

#### Repeat field

- Upgrade the back views so they use the new page template and components
- Repeat field should be localized as a whole. This provides more consistent behaviour. Any field items should no longer
  be localized. You can run a chief command `chief:localize-repeat-field {resourceKey} {key}` to convert database values
  to the new format. 
