# Changelog

All notable changes to the `chief` application template will be documented in this file. Updates should follow
the [Keep a CHANGELOG](http://keepachangelog.com/)
principles.

## Unreleased - upcoming 0.9.0
Not backward compatible for fragment. Please run migrations since this update involves some database changes as well.

todo: fields owner as parameter resource owner and fragment if nested. Fields parameter of fragments is not resource so first param fragment is not needed.
- migratie voor context on fragments to adjacent tree structure
- tests voor actions, queries, controllers, UI components, livewire and views
- Remove Assistants folder.
- remove FragmentResource stuff -> just fragmentable guys
- use: Resource (as base model), Fragment
- Changed: **Config change!** `chief.locales` in config now contains two entries: `chief.locales.admin` which contain all available locales and `chief.locales.site`that hold the active locales in usage on the site.
-   Changed: Renamed `FragmentAdded` event tot `FragmentAttached` for consistency.
- Changed: `Fragmentable::fragmentModel()` always returns an existing model. Else A `MissingFragmentModelException` is thrown.
- vine dep upgrades...

### Code architecture
All the fragment logic has been moved to the `Thinktomorrow\Chief\Fragments` namespace and cleanup up
- Renamed interface `Thinktomorrow\Chief\Fragments\Fragmentable` to ``Thinktomorrow\Chief\Fragments\Fragment``.
- Removed `Thinktomorrow\Chief\ManagedModels\Presets\Fragment`. Replaced by `Thinktomorrow\Chief\Fragments\Fragment`.
  A fragment class should look something like this:
```php
use Thinktomorrow\Chief\Fragments\BaseFragment;
use Thinktomorrow\Chief\Fragments\Assistants\ForwardFragmentProperties;

class Image extends BaseFragment
{
    use ForwardFragmentProperties;
    ...
}

{
    use ForwardFragmentProperties;
    use BookmarkDefaults;
    
    ...
}
```

- If you have a viewPath property in your fragment, this should be protected or public, not private.
  Replace in each fragment class `private (string) $viewPath` with `protected ?string $viewPath`.
- Removed usage of `private $baseViewPath` to set the path of the fragment. To reduce complexity, we allow to set a custom viewPath via a `protected ?string $viewPath` but no longer allow to only set a base path on its own.
- After migration has run, the fragments records will contain a `key` column which represents the fragment class key. This is no longer in the format of <key>@0, but solely the key.
- Removed: soft deletion state of a fragment. Now a deleted fragment is hard deleted.

### Fragment rendering
Fragments now behave and render in the views as blade components. This is similar to the way the form fields are rendered.

### Menu
- Changed: Menu rendering is now done with the new vine logic. 
