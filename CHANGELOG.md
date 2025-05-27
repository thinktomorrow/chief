# Changelog

All notable changes to the `chief` application template will be documented in this file. Updates follow
the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## Unreleased

- Fixed: Multiple checkbox/select options in Livewire forms were not properly handled.
- Added: `chief:default-menus` command to add default menus to the database.
- Added: Highlight active nav item in the admin panel.
- Changed: ⚠ BC break `chief.menus` config is now structured differently. Each key is the type and value is its label.
- Changed: Now using Vite instead of Laravel Mix to build all Chief assets
- Changed: Upgraded TailwindCSS to v4. Refactored the existing tailwind.config.js to the new CSS config.
- Changed: Upgraded all other JS packages to their latest versions

## 0.10.6 - 2025-05-22

- Fixed: Adding allowed sites to model didn't sync yet with the context in case of one default context.
- Fixed: instant update of sites sync

## 0.10.5 - 2025-05-22

- Added: config `allow_site_selection` to allow changing sites selection in the admin panel. This can be set per model
  via the `allowSiteSelection(): bool` method.
- Added: config `allow_multiple_contexts` and `allow_multiple_menus` to allow the admin to add multiple fragment
  contexts / menus.
- Added: Localized field indicator next to form label.

### Multisite as config

With this PR the multisite functionality is now a config option. This means that you can activate Multisite on a project
by setting following config:

```php
'allow_site_selection' => true,
'allow_multiple_contexts' => true,
'allow_multiple_menus' => true,
```

## 0.10.4 - 2025-05-20

- Fixed: export/import of translations for repeat fields
- Fixed: links mgmt for models that don't implement HasAllowedSites
- Changed: Spaces in links are no longer allowed and are now automatically replaced with hyphens.
- Changed: Refactor locales on create model component
- Added: `showsLocales` on a field to indicate if field show be shown localized. This is for presentation purposes and
  differs from `hasLocales` which indicates if the field is localized.

## 0.10.3 - 2025-05-15

- Fixed: Unauthenticated redirectTo requires Request as parameter.
- Fixed: Issue where change to homepage of a nestable model conflicted when propagating url changes with parent slug.
- Fixed: Homepage link is no longer deletable via the edit-links of a page
- Fixed: Rendering table column for a relation attribute e.g. parent.title of a BelongsTo relation was broken.
- Changed: Locale toggle in form edit view is now hidden when no localized fields are present.
- Added: larastan github action to run static analysis on pull requests
- Added: Verify if column item can be rendered in table.
- Removed: `CHIEF_DISABLE_FIELD_REQUIRED_VALIDATION` option that allowed you to override required rules for local
  development.

## 0.10.2 - 2025-05-11

- Fixed: Propagate url change for nestable models. Also allow homepage urls to propagate to child models.

## 0.10.1 - 2025-05-11

- Changed: (breaking) Repeat items are no longer allowed to be localized. Localize the repeat field instead. You can run
  the migrate command `chief:localize-repeat-field {classes} {key}` to convert the json data to the new format.
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
