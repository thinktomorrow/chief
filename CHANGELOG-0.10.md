# Changelog

All notable changes to the `chief` application template will be documented in this file. Updates should follow
the [Keep a CHANGELOG](http://keepachangelog.com/)
principles.

## Unreleased - upcoming 0.10.0

Not backward compatible for fragment. Please run migrations since this update involves some database changes as well.

todo: fields owner as parameter resource owner and fragment if nested. Fields parameter of fragments is not resource so
first param fragment is not needed.

- migratie voor context on fragments to adjacent tree structure
- tests voor actions, queries, controllers, UI components, livewire and views
- Remove Assistants folder.
- remove FragmentResource stuff -> just fragmentable guys
- use: Resource (as base model), Fragment
- Changed: **Config change!** `chief.locales` in config now contains two entries: `chief.locales.admin` which contain
  all available locales and `chief.locales.site`that hold the active locales in usage on the site.
- Changed: Renamed `FragmentAdded` event tot `FragmentAttached` for consistency.
- Changed: `Fragmentable::fragmentModel()` always returns an existing model. Else A `MissingFragmentModelException` is
  thrown.
- vine dep upgrades...

- Fragment field tags are now in use. In prior releases, the fields tags 'not-on-create' were not being used by
  fragments and fields were always shown and saved.

### Code architecture

All the fragment logic has been moved to the `Thinktomorrow\Chief\Fragments` namespace and cleanup up.

- A fragment class should extend the BaseFragment. All of the logic is in that base class. A fragment class should look
  something like this:

```php
use Thinktomorrow\Chief\Fragments\BaseFragment;

class Image extends BaseFragment
{
    ...
}
```

- Moved trait `Thinktomorrow\Chief\Fragments\Assistants\ForwardFragmentProperties` to
  `Thinktomorrow\Chief\Fragments\Models\ForwardFragmentProperties`. It can also be removed because this is included in
  the BaseFragment class.
- Renamed interface `Thinktomorrow\Chief\Fragments\Fragmentable` to `Thinktomorrow\Chief\Fragments\Fragment`.
- Removed trait `Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults` in favor of the
  `Thinktomorrow\Chief\Fragments\BaseFragment` abstract class.
- Deprecated `Thinktomorrow\Chief\Fragments\Fragment::fragmentModel()` in favor of
  `Thinktomorrow\Chief\Fragments\Fragment::getFragmentModel()`.
  class.
- Deprecated interface `Thinktomorrow\Chief\ManagedModels\Presets\Page`. Use `Thinktomorrow\Chief\Models\Page`.
- Removed interface `Thinktomorrow\Chief\ManagedModels\Assistants\PageDefaults`. Use
  `Thinktomorrow\Chief\Models\PageDefaults`.
- Removed class `Thinktomorrow\Chief\Managers\Presets\FragmentManager`. Fragment controllers are used now instead of the
  Resource manager structure.
- Removed class `FragmentAssistant`.
- Removed trait `Thinktomorrow\Chief\ManagedModels\Assistants\ModelDefaults`. Use
  `Thinktomorrow\Chief\Models\ModelDefaults`.
- Removed interface `Thinktomorrow\Chief\ManagedModels\Assistants\ShowsPageState`. Use
  `Thinktomorrow\Chief\Models\ShowsPageState`.
- Removed interface `Thinktomorrow\Chief\ManagedModels\Presets\Fragment`. Replaced by new interface
  `Thinktomorrow\Chief\Fragments\Fragment`.
- Moved interface`Thinktomorrow\Chief\Fragments\Assistants\HasBookmark` to
  `Thinktomorrow\Chief\Fragments\Sections\HasBookmark`.
- Changed: `Thinktomorrow\Chief\Models\ModelDefaults` is slimmed down and no longer contains the
  `Thinktomorrow\AssetLibrary\InteractsWithAssets` and `Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable` trait
  behaviour. You'll need to add this and the necessary interfaces to your projects models if required.

- If you have a viewPath property in your fragment, this should be protected or public, not private.
  Replace in each fragment class `private (string) $viewPath` with `protected ?string $viewPath`.
- Removed: Fragment::$baseViewPath property. Usage of `private $baseViewPath` to set the path of the fragment. To reduce
  complexity, we allow to set a custom viewPath via a `protected ?string $viewPath` but no longer allow to only set a
  base path on its own.
- After migration has run, the fragments records will contain a `key` column which represents the fragment class key.
  This is no longer in the format of <key>@0, but solely the key.
- Removed: soft deletion state of a fragment. Now a deleted fragment is hard deleted.
- Added: config option `fragment_viewpath` to set the default view path for fragments.

### Fragment rendering

Fragments now behave and render in the views as blade components. This is similar to the way the form fields are
rendered. These changes should make fragment rendering more structured, predictable, and easier to extend.

Refactored rendering logic to introduce clearer extensibility and standard view component behaviour.
Replaced old renderFragment() and renderAdminFragment() methods with new render() and renderInAdmin() methods.

- Added: `render()` and `renderInAdmin()` methods.
- Added: `viewData()` method to pass data to the fragment view. The default data payload is:
    - `fragment` - the fragment model
    - `section` - the root fragment (if any)
    - `model` - deprecated in favor of `fragment`
- Properties `viewPath` and `adminViewPath` allow to set the view path of the fragment.
- Removed: `renderFragment()` and `renderAdminFragment()` methods.

### New glossary

#### Section

Root fragments of each context. This is the top level fragment of a context. A section can contain multiple fragments.
Sections generally contain the layout options like background color, text color, positioning of text and images, grid
display settings, ...
Sections are the html elements that can be referenced via bookmarks.

### Menu

- Changed: Menu rendering is now done with the new vine logic. 
