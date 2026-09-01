# Changelog

All notable changes to the `chief` application template will be documented in this file. Updates follow
the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## Unreleased

## [0.10.31] - 2026-09-01

- Added: State configs can implement `StateTransitionGuard` to reject a transition before any changes are persisted.
- Fixed: Date, time and datetime fields now format immutable date values. A `CarbonImmutable` value was passed through
  unformatted, which the browser rejected and rendered as an empty input.
- Fixed: The `min`, `max` and `step` of a date, time, datetime, number or slider field are no longer lost when the field
  is nested in a repeat field. A custom `step` silently reverted to the field's own default.
- Fixed: Field previews no longer throw when the stored value cannot be parsed as a date. The raw value is shown
  instead.
- Fixed: Don't render table filter if no options are available.
- Added: option to add project nav items to the settings nav. Tag your nav item with `nav-settings`
- Added: A Datetime field to enter a combined date and time value. `\Thinktomorrow\Chief\Forms\Fields\Datetime::class`.
- Added: Table option to set amount of items per page.
- Added: `Table::paginate()` now accepts second parameter to set the default items per page.
- Added: `size` attribute on the `x-chief::form.input.select` component to render a small (`sm`) or default (`md`)
  variant. Backed by the new `form-input-field-sm` and `form-input-field-md` css utilities.
- Changed: new require laravel/pint: ^1.30

## [0.10.30] - 2026-08-31

- **Breaking change**: The `invitations` table is renamed to `chief_users_invitations`.
- **Breaking change**: `FileApplication::updateAssociatedAssetData()` now requires an `associatedFieldKeys` array.
  Callers must explicitly pass the keys of fields whose values should be stored on the asset association.

- Fixed: Pint 1.30 formatting across the entire codebase.
- Fixed: Table columns now resolve protected Eloquent attribute mutators as properties and provide actionable context
  when a model value cannot be rendered. This resolves the $model->key () issue where key is a protected eloquent
  method.
- Fixed: State transitions now redirect to the resource index when their model was already deleted, and confirmation
  buttons are disabled while the transition request is running.
- Fixed: The save button is now shown when editing invited or blocked admin users.
- Fixed: default field values in state confirmations modals are now correctly set to the current/default value of the
  field.
- Fixed: Duplicated model no longer keeps state of the original model, such as archived, published, ... And always
  resets the state to default values.
- Fixed: On an detailpage, the link window now shows a helpful message in case of a missing link
- Fixed: File upload tests now support the signed temporary upload paths returned by Livewire 3.8.6.
- Added: `MenuItemResource` interface to allow projects to define their own menu item resource with custom fields and
  table layout. See `DefaultMenuItemResource` for an example implementation.
- Added: Menu items can now be configured with custom project fields and table layout. This is done via a custom
  `MenuItemResource` class.
- Added: Menu item fields and table configuration can vary per `Menu`; field definitions receive a `MenuItem` with its
  `menu` relation loaded and `configureTable()` receives the active `Menu` explicitly.
- Added: Chief users can now be permanently deleted while audit history and invitations sent by the user retain
  immutable user snapshots.
- Changed: The `invitations` table is renamed to `chief_users_invitations`. The migration backfills inviter and audit
  snapshots, cascades invitations when their invitee is deleted, and retains invitations with a nullable inviter.
- Changed: The minimum `thinktomorrow/assetlibrary` version is now 1.0.3.

## [0.10.29] - 2026-08-25

- Fixed: The `icon` attribute on a grouped `<x-chief::nav>` is no longer ignored. It now sets the icon of the dropdown
  group, and only falls back to the icon of the first tagged resource when omitted.
- Fixed: Dialog components (modals, drawers, dropdowns) are now teleported to the body instead of being rendered in
  place. This prevents them from being clipped or stacked incorrectly when they live inside another dialog, window or
  overflow container. Applies to fragments, contexts, menus, links, site selection, forms, state, file upload/edit and
  the table dialogs.
- Fixed: Multiple tables on the same page no longer share dialog ids. The filters drawer, sorting dropdown, column
  selection, table actions and bulk actions dialogs are now scoped per table component, so opening one no longer
  triggers the dialog of another table.
- Fixed: The table filter overflow calculation now only looks at its own table header instead of the first one on the
  page, and no longer loops endlessly when there are no filters left to move to the drawer.
- Fixed: The site/locale toggle on the settings page is no longer shown when only one site is active.
- Fixed: The permissions multiselect on the role form now submits its values as an array, so selecting multiple
  permissions no longer overwrites the role's permissions with only the last selected one.
- Fixed: The table now shows a scrollbar when its content overflows horizontally, instead of hiding it.
- Fixed: Logging back in right after logging out could bounce you back to the login form on the first attempt. The
  session middleware no longer re-stores the admin's password hash after a logout has already invalidated the session.
- Changed: renamed default simple state label to 'online' and 'offline' (was 'gepubliceerd' and 'draft')

## [0.10.28] - 2026-07-28

Improved permissions; Scoped table filters; Updated Squanto package and UI; Requires thinktomorrow/squanto:6.*

- Added: `permissionScope` method to Resource interface to allow scoping permissions per resource type.
- Added: `permissionAbilities` method to Resource interface to allow scoping permissions per resource type.
- Added: Duplicating nested fragments is now supported.
- Added: `Filter::scoped` - Table filters can now scope filter and sorter session state per active scope value.
- Added: Table select filter option callbacks now receive the active table filters.
- Fixed: The content of fragments with nested fragments now shows the correct preview in 'existing fragments' sidebar
  tab.
- Fixed: Table button group filters now keep their active marker aligned while Livewire updates option labels
- Fixed: Tabs now keep their active marker aligned while Livewire updates
- Fixed: Scalar filter values are now also retrieved in getFilterValueFromOptions
- Fixed: download button in media gallery is working again
- Fixed: Show livewire stale session modal when debug mode is enabled
- Fixed: Saving dialog no longer immediately closes the dialog
- Changed: Squanto index grid UI instead of list in window

## [0.10.27] - 2026-07-16

- Added: no index headers for all admin routes to prevent search engines from indexing admin pages
- Added: Eslint unicorn plugin for better, recommended js linting. Fixed all eslint errors/warnings that surfaced after
  implementing this new plugin.
- Changed: TimeTable styling tweaks for a more compact and uniform layout
- Fixed: new menu child items are now appended to the end of their selected parent list instead of landing in an
  unstable position
- Fixed: Redactor toolbar is now correctly showing up if custom redactor options are defined on a text field
- Fixed: Table select filter horizontal scroll without scrollbar + larger dropdown width
- Fixed: Field preview slot now doesn't overflow content of windows anymore
- Fixed: Chief favicon path doesn't break while running Vite dev build
- Fixed: HotSpots plugin now supports localized fields — locale toggle is rendered when multiple locales are active, and
  localized field values are correctly stored and validated per locale.
- Fixed: HotSpots file-edit-action button now correctly opens its modal on top of existing modals/drawers.

## [0.10.26] - 2026-06-16

- Fixed: time preview now shows in 24-h format instead of 12-h
- Fixed: Issue where TimeTable exceptions were collections but are now expected to be arrays by the Spatie/OpeningHours
  package.
- Fixed: TimeTable plugin was outdated and is now updated to latest Chief version.
- Added: Register::fragments () to get all registered fragments
- Changed: updated packages for security vulnerabilities

## [0.10.25] - 2026-04-30

**This release contains breaking changes!**

- Changed: Chief now writes `sitemap.xml`, `sitemap-{locale}.xml`, `image-sitemap.xml`, and
  `image-sitemap-{locale}.xml` to `storage/app/feeds` instead of `public/`.
- Breaking/upgrade note: expose `storage/app/feeds` publicly in your host app, by adding a `feeds`
  filesystem disk and a `filesystems.links` entry, then running `php artisan storage:link`.
- Breaking/upgrade note: review and update sitemap references after upgrading, such as in `robots.txt`, Google Search
  Console, and any other external integrations.

## [0.10.24] - 2026-04-27

- Fixed: Password reset token expired after 60 seconds instead of 60 minutes.
- Fixed: use db table in Table filter conflicted with relation search. e.g. shopper.title expected shopper to be
  relation of model, not per se a table.
- Changed: Using Laravel Password broker and resolver instead of custom classes.

## [0.10.23] - 2026-04-20

- Fixed: Ignore non-found owners for orphaned fragments (can occur on shared fragments where a parent is deleted)
- Added: not-found error page

## [0.10.22] - 2026-04-14

- Fixed: centralised Livewire session handling in admin with graceful 419 recovery (auto-reload once, then refresh
  dialog) and retained 500 error dialog handling.
- Added: admin keep-alive endpoint and periodic session ping to reduce 419 errors on long-lived Chief screens.
- Fixed: teased text columns now keep full values in the hover tooltip title.
- Fixed: adding an existing fragment now respects the selected insertion position instead of being appended at the end.
- Fixed: Error modal was not visible when a modal was opened. (z-index issue)
- Fixed: file-edit submit no longer crashes when model reference is missing in Livewire hydration.
- Fixed: menu links are now refreshed after changing homepage in settings.
- Fixed: blocked users can no longer be re-authenticated via remember-me token.
- Fixed: Repeat rows now keep field state attached to the correct row after deleting or reordering items.
- Added: updated dependencies to latest version both composer.json and package.json
- Added: `Fragment:setContextOwner()` and `Fragment::getContextOwner()` to allow to have owner model for e.g. fields ().
- Added: Boost guideline doc
- Changed: Title filter in table now defaults to searching combo of multiple input values (separated by spaces) - and
  not the entire input value.

## [0.10.21] - 2026-03-25

- Fixed: Context owning model now always have at least one context. This fixes the issue where model becomes context
  owner after creation, no context was available so no fragments could be added.
- Fixed: Boolean field rendered incorrect preview
- Fixed: Grid fields were always placed at the end of the form
- Fixed: single-locale fields in file form showed a locale toggle which was excessive UX.
- Fixed: selected option was not highlighted in multiselect options list
- Fixed: Prefer table tree view as default instead of manually sorted view
- Added: basic artisan support
- Added: Laravel boost skills
- Added: Squanto basic search
- Added: Form::reloadAfterSave method to reload page after saving this form
- Added: Show a new tree breadcrumbs column when in Table filtering/sorting mode. This can be (de)selected as column
- Added: phpstan baseline to temporary ignore error stack
- Added: better indices on asset pivot table (Run migrations)
- Added: lazy load existing fragments tab (speeds up adding fragments sidebar)
- Changed: Fragments are now lazy loaded on scroll intersection. This is still a bit experimental but should already
  give you a speed increase.
- Changed: On first publication of visitable model, all links are put online automatically
- Changed: selectList search input is preserved after adding items
- Changed: breadcrumbs teasers are now generated in PageResourceDefault instead of breadcrumbs component

## [0.10.20] - 2026-03-17

- Fixed: Initial field values were not showing in Dialog component
- Added: Field::hideInPreview () to only show form field in form and not in form preview.
- Added: Table ArchiveRowAction preset.
- Deprecated: hideIfEmpty (). Use hideInPreviewIfEmpty () instead to only hide field in preview when empty.

## [0.10.19] - 2026-01-27

- Added: Support for php 8.5
- Changed: Checkbox is now multiple by default since this is the default behaviour of checkboxes.
- Changed: ContextOwner now requires `allowContexts()` method to indicate if a model allows to manage fragment contexts
  or not

## [0.10.18] - 2026-01-26

- Fixed: Instance attributes on create model component were not properly set.
- Fixed: Authentication middleware did not properly log the admin out.
- Fixed: Disabled users stayed logged in when a remember me cookie was used.
- Fixed: Editor scripts were loading twice in squanto, causing multiple redactor instances. Just a single include now!
- Fixed: An issue where file upload could not be used in create form. Fixed by unidentified model reference (see below).
- Changed: Cleaner authentication logic
- Added: ModelReference now has 'unidentified' status for model that isn't persisted yet.

## [0.10.17] - 2025-12-08

- Added: viewData parameter to setView () and setPreviewView () on Form layout/field classes.
- Fixed: selection option could not be removed in select field. X button was missing.
- Fixed: Table action with dialog can now handle a response such as download or redirect.

## [0.10.16] - 2025-12-01

- Added: Allow to hide field in preview if empty via `hideIfEmpty()` method on field.
- Added: Preserve table filtering and sorting across page reloads.
- Added: empty fragment view for contentless fragment preview in admin. Place this in the renderInAdmin method:
  `return view('chief-fragments::empty');`
- Added: sidebar form can now be saved on enter key press.
- Added: Docs plugin: basic user docs via markdown per project
- Added: HasDialogSize trait to change the size of modals and drawers in forms
- Fixed: table bulk select was broken due to table components
- Fixed: creation of non-localized models in multisite setup
- Fixed: Menu item validation
- Fixed: DeepCopy package as required dependency for Chief. Was previously only in dev dependencies.
- Fixed: Repeat field now shows redactor field in new repeat additions
- Fixed: Html field in table dialog wasn't loaded as wysiwyg editor.
- Fixed: Form tags now work for all underlying fields. Form tagged as not-on-model-create will no longer show the fields
  on the model create form.
- Fixed: Livewire errors now show a custom dialog instead of trying to show the frontend error page.
- Fixed: Relations via select::sync () where not loaded on create model form.
- Changed: Link form has better display when only one locale is active.
- Changed: Consistent save behaviour for fields with multiple values. Primitive or null value for non multiple, array or
  empty array when set to multiple. Applies to Select, MultiSelect, Checkbox, SelectList.

## [0.10.15] - 2025-09-23

- Fixed: bug in user invitation views
- Changed: Default table now has view on site as primary row action

## [0.10.14] - 2025-09-18

- Fixed: reordering menu items + menu tree structure was no longer visible
- Fixed: Table Reference with a modelReference as parameter broke dialogs in table.
- Fixed: Cleanup any sequential slashes e.g. "my//slug" -> "my/slug"
- Added: Window Layout Component
- Added: DeleteRowAction to delete model without state
- Added: `Table::disallowColumnSelection()` to disable column selection for a table.
- Added: Basic Table Components. These are used by the Livewire Table Component for displaying default data. The basic
  components can be used to build a custom table with the same layout.
- Changed: AdminToast element layout update
- Changed: Archivable trait now allows custom column name and archived value.

## [0.10.13] - 2025-09-10

- Fixed: Context fragments that were removed broke adding new fragments to the context.

## [0.10.12] - 2025-09-10

- Fixed: select options with empty values were not properly handled in forms.
- Fixed: SelectList now filters out selected values that are not present in full options list.
- Fixed: Table columns are now rendered in given site filter. E.g. filtering on site 'nl' will now also render columns
  with localized values for 'nl'.
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

- Introduces column-selection UI in tables with backend support via new Livewire concerns (`WithColumns`,
  `WithColumnSelection`).
- Adds a Chief SEO plugin (Livewire tables, controllers, routes, Blade views, and console commands)
  to export / import asset filenames and alt texts.
- Integrates a Hive AI plugin: Alpine directives, service providers, prompts, and form-field enhancements for AI-powered
  text suggestions.
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
- Added: `MemoizedSelectOptions` to avoid duplicate queries when using the same select options in multiple places.
- Changed: `Resource::getAttributesOnCreate` now has input values as its parameter, which allows you to set any of these
  values as model attributes on create.

### Seo Asset table

Introduces first version of asset management, which allows you to easily manage filename and alt attributes of each
asset. Every Asset model should implement the `HasAlt` interface as well as the `ReferencesModel` to be able to use the
seo table.

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
- Changed: Actions::when () closure has now a first parameter of `component` instead of `model`, which is now second
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
- Removed: The previously deprecated `custom-scripts-after-vue` stack (loaded in the page layout) was removed. Use the
  `custom-scripts` stack instead.
- Removed: SVG symbols file. All projects referring to SVG icons by id with `xlink:href`, should now use full SVG icons
  instead.

## 0.10.0 - 2025-05-08

You should follow the upgrade guide for upgrading any existing projects from 0.9 to 0.10. Please run migrations, as this
update involves database changes, especially for the fragment tables.

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
