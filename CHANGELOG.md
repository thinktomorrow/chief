# Changelog

All notable changes to the `chief` application template will be documented in this file. Updates follow
the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## Unreleased

- Fixed: select options with empty values were not properly handled in forms.
- Fixed: SelectList now filters out selected values that are not present in full options list.
- Fixed: Table columns are now rendered in given site filter. E.g. filtering on site 'nl' will now also render
  columns with localized values for 'nl'.
- Fixed: Menu item of type 'no link' now has any existing links removed when saving.
- Added: Hive option for exporting resource and squanto texts. Use `--hive` option on the `chief:export-resource` and
  `chief:export-text` commands.
- Added: Table now show the localized value based on the current site filter.
- Added: PreviewFile is now optional parameter for `Asset::fields($previewFile)`;
- Added: Table sorting is now stored in session.
- Changed: No pagination while reordering
- Changed: Redirect to edit page after creation of a model

## [0.10.11] - 2025-08-25

- Fixed: Character count elements under some field types were missing and are now added to the view.
- Fixed: External assets field components site/locale toggle was missing and is now added to the view.
- Added: AssetColumnImage preset for table columns to show asset images.
- Change: small improvements on hive such as adding default context to config.
- Change: Character count script refactored to alpinejs function so it works everywhere with Livewire.
- Change: HasCharacterCount now also available for Html fields

## [0.10.10] - 2025-08-19

### Overview

- Introduces column-selection UI in tables with backend support via new Livewire concerns
  (`WithColumns`, `WithColumnSelection`).
- Adds a Chief SEO plugin (Livewire tables, controllers, routes, Blade views, and console commands)
  to export / import asset filenames and alt texts.
- Integrates a Hive AI plugin: Alpine directives, service providers, prompts, and form-field enhancements
  for AI-powered text suggestions.
- Added a new SelectList field and Boolean field.

### Forms

- **Breaking change** Removed: `showAsToggle()` method on fields. Only the new Boolean field will always be shown as a
  toggle. Be sure to remove this on field definitions in your projects.
- Fixed: non-unique checkbox/radio ids resulted in only toggling first checkbox/radio in forms
- Fixed: issue with mapping original null value
- Added: HasPreviewLabel trait to make distinction between labels used in form and/or form preview. This way the Boolean
  field can show the new optionLabel as preview label instead.
- Added: Select list field
- Added: Boolean field with new optionLabel and optionDescription methods.
- Added: Loading indicator on save buttons in sidebar dialog.
- Added: `Thinktomorrow\Chief\Forms\Layouts\Layout` interface to allow placement of non form elements on a page
- Added: `Field::getOriginalValue()` method to retrieve the original value of a column item before it was mapped. This
  impacts variant mapping of a column (see below)
- Changed: All wire:model field bindings are now deferred and no longer updated on blur or change. They are also wire:
  ignored. Except repeat fields are still live. This was mainly for support of nested repeat fields, which are no longer
  supported.
- Changed: Refactor locale toggles in forms and fragments to use one partial file.

### Assets

- Added: asset alt and filename export / import
- Added: WIP version for hive AI integration

### Tables

- Fixed: Reordering tree items in table showed wrong results.
- Fixed: use sortable attribute for table sorting
- Fixed: issue when using grouped select filter in table
- Added: Table column selection
- Changed: Variant mapping of a column item now used the original value instead of any mapped value.
- Changed: improve flexibility of default table actions and ordering logic.

### Models

- Fixed: On create fragment, the file field was not saved.
- Added: Edit model livewire component to allow editing models in dialog
- Added: Parameter `redirectAfterSave` to instruct CreateModelComponent to close or redirect to the new page after save.
- Added: option on `getTreeModels(?array $ids = null, array $eagerLoading = ['urls', 'tags']))` to select eager
  relations. This parameter is also available on the `SelectOptions::getTree()` method.
- Added: `MemoizedSelectOptions` to avoid duplicate queries when using the same select options in multiple
  places.
- Changed: `Resource::getAttributesOnCreate` now has input values as its parameter, which allows you to
  set any of these values as model attributes on create.

### Seo Asset table

Introduces first version of asset management, which allows you to easily manage filename and alt attributes of each
asset.
Every Asset model should implement the `HasAlt` interface as well as the `ReferencesModel` to be able to use the seo
table.

The alt field definition on an Asset class should look like this:

```php
public function fields(): iterable
{
    yield Text::make('alt')
        ->locales()
        ->label('Alt tekst')
        ->value(fn ($model, $locale) => $model->getAlt($locale));
}
```

## [0.10.9] - 2025-06-19

- Fixed: On create model dialog, the file field disappeared when switching locale

## [0.10.8] - 2025-06-11

This release introduces the new sorting UI for the table indices and contains breaking changes regarding the Sortable
logic!

- Fixed: show scopedLocale in edit parent fragment even when no localized fields are present so fragments can be
  previewed per locale.
- Added: **⚠ BC break** Sortable models now require the `Thinktomorrow\Chief\Shared\Concerns\Sortable\Sortable`
  interface.
- Changed: **⚠ BC break** Renamed `Thinktomorrow\Chief\Shared\Concerns\Sortable` trait to
  `Thinktomorrow\Chief\Shared\Concerns\Sortable\SortableDefault`.
- Changed: Actions::when() closure has now a first parameter of `component` instead of `model`, which is now second
  parameter.
- Changed: Make password 'missing login' error less clear for security reasons.

## [0.10.7] - 2025-06-03

This release contains breaking changes!

- Fixed: Pending file changes were stored but initial state was shown to admin when toggling between locales.
- Fixed: Menu item label and url were not saved when creating/editing a menu item.
- Fixed: Multiple checkbox options in Livewire forms were not properly handled.
- Added: `chief:default-menus` command to add default menus to the database.
- Added: Highlight active nav item in the admin panel.
- Changed: **⚠ BC break** `chief.menus` config is now structured differently. Each key is the type and value is its
  label. View the upgrade guide for more details.
- Changed: **⚠ BC break** Upgraded TailwindCSS to v4. Refactored the existing tailwind.config.js to the new CSS config.
  Also make sure to update the redactor-styles.css. View the upgrade guide for more details.
- Changed: Now using Vite instead of Laravel Mix to build all Chief assets
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
