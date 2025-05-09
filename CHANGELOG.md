# Changelog

All notable changes to the `chief` application template will be documented in this file. Updates follow
the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## Unreleased

- Removed: The previously deprecated `custom-scripts-after-vue` stack (loaded in the page layout) was removed.
  Use the `custom-scripts` stack instead.
- Removed: SVG symbols file. All projects referring to SVG icons by id with `xlink:href`, should now use full SVG icons
  instead.

## 0.10.0 - 2025-05-08

You should follow the upgrade guide for upgrading any existing projects from 0.9 to 0.10.
Please run migrations, as this update involves database changes, especially for the fragment tables.

---

### Added

- `render()` and `renderInAdmin()` methods to fragments.
- `viewData()` method to pass data to fragment views (`fragment`, `section`, and deprecated `model`).
- Config option `fragment_viewpath` to set default view path for fragments.
- New Blade components:
    - `x-chief::dialog.drawer`
        - `x-chief::dialog.drawer.header`
        - `x-chief::dialog.drawer.footer`
    - Refactored `x-chief::dialog.modal`
        - `x-chief::dialog.modal.header`
        - `x-chief::dialog.modal.footer`
    - `x-chief::form.fieldset` (replaces `x-chief::input.group`)
- New `size` attribute for `x-chief::tabs`: options are `xs`, `sm`, `base`.

---

### Changed

- **Config:**

    - `chief.sites`: site mgmt replacing `chief.locales`.
    - Diacritics are now converted to ascii for all links.

- **State:**

    - Added `scopeWithOnlineUrl` to Visitable interface to check if model has online url for given site.
    - Added `rawUrl` to Visitable interface to check if model has online url for given site.
    - former `scopeOnline` checked for the 'published' state on a page. This is now renamed to `scopePublished`.
    - `scopeOnline` is now added as method to the Visitable interface.
    - `scopeOnline` is now a general wrapper scope that checks for:
        - whether page is published,
        - whether page has an online url for the given site
        - whether page is allowed on the given site

- **Fragments:**

    - Renamed `FragmentAdded` event to `FragmentAttached`.
    - `Fragmentable::fragmentModel()` now throws `MissingFragmentModelException` if no model found.
    - Fragment classes must now extend `BaseFragment`.
    - `viewPath` property in fragment classes must be `protected` or `public`, not `private`.
    - Removed `Fragment::$baseViewPath`; use `protected ?string $viewPath` instead.
    - After migration, `fragments.key` is no longer in `<key>@0` format but simply the key.
    - Fragments now render as Blade components (like form fields).
    - Replaced `renderFragment()` and `renderAdminFragment()` with `render()` and `renderInAdmin()`.

- **Form Livewire Component:**

    - Removed methods: `Form::action()`, `Form::windowAction()`, `Form::refreshUrl()`, `Form::redirectAfterSubmit()`.
    - Removed `Field::editInSidebar()` and `Field::editInline()`.
    - Use `Form::view()` instead of `Form::windowContainerView()` or `Form::previewView()`.
    - Removed `Form::ProtectAgainstFill` trait.
    - Obsolete scripts removed.

- **Form Components Cleanup:**

    - All `x-chief::button` updated to `x-chief::button` (was `x-chief-table::button`).
    - `x-chief::link` now follows `x-chief::button` API.
    - Replaced legacy CSS files:
        - `form.css`
        - `button.css` → replaced by `bui-button.css`, then renamed back to `button.css`
        - `link.css` → replaced by `bui-link.css`, then renamed back to `link.css`

- **Renamed Components:**

    - `x-chief::form.label` → was `x-chief::input.label`
    - `x-chief::form.description` → was `x-chief::input.description`
    - `x-chief::form.error` → was `x-chief::input.error`

- **ModelDefaults:**

    - Now does **not** include:
        - `InteractsWithAssets`
        - `Viewable`
    - You must manually add these to your models if needed.

- **Menu:** now uses Vine logic for rendering.

---

### Removed

- Entire `Assistants` folder
- All `FragmentResource` logic; now handled through fragmentable models
- Trait: `FragmentableDefaults` (replaced by `BaseFragment`)
- Trait: `ModelDefaults` (replaced by `Chief\Models\ModelDefaults`)
- Interface: `Fragmentable` → renamed to `Fragment`
- Interface: `Presets\Fragment` → replaced by `Fragments\Fragment`
- Interface: `Presets\Page` → replaced by `Models\Page`
- Interface: `PageDefaults` → replaced by `Models\PageDefaults`
- Interface: `ShowsPageState`
- Interface: `Assistants\PageDefaults`
- Class: `FragmentAssistant`
- Class: `FragmentManager` (controllers are now used instead)
- Trait: `ForwardFragmentProperties` moved to `Models\ForwardFragmentProperties` and included in `BaseFragment`
- Fragment soft deletes — now permanently deleted (hard delete)
- Obsolete Blade components:
    - `x-chief::copy-button`
    - `x-chief::icon-label`
    - `x-chief::icon-button`
    - `x-chief::inline-notification`
    - `x-chief::hierarchy`

---

### Deprecated

- Method: `fragmentModel()` → use `getFragmentModel()` instead

---

### Code Architecture

All fragment logic moved to `Thinktomorrow\Chief\Fragments` namespace.

- Fragments should extend `BaseFragment`:

    ```php
    use Thinktomorrow\Chief\Fragments\BaseFragment;

    class Image extends BaseFragment
    {
        ...
    }
    ```
