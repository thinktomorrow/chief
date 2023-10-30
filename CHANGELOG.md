# Changelog

All notable changes to the `chief` application template will be documented in this file. Updates should follow
the [Keep a CHANGELOG](http://keepachangelog.com/)
principles.

## Unreleased

## 0.8.4 - 2023-10-30

- Added: `Select::syncInOrder()` to force synced values in given order. 
- Fixed: Sorting of select options sync values was not kept in given order. 
- Fixed: Sorting behavior on asset files was buggy.
- Fixed: Buggy update listeners in asset gallery component.
- Fixed: animated gifs and svg images are no longer converted when uploaded. Here the original sources will always be used instead.
- Fixed: Conditional fields were not visible on page/sidebar load when based on the (new) MultiSelect.
- Fixed: Conditional fields logic was initiated x times every form on the page. Now it is only loaded once.
- Changed: upgraded `thinktomorrow/assetlibrary` to 0.9.5.

## 0.8.3 - 2023-10-24

- Changed: All Vue components are rebuilt using Blade components, alpine and Livewire.
- Changed: 'custom-scripts-after-vue' stack is now obsolete, but still supported. Use 'custom-scripts' instead.
- Fixed: If multiselect sync() was set up, the options of nestable would not show with the breadcrumbs in the select
  options.
- Fixed: Roles selection on admin invite form was not stored as array.
- Removed: Vue
- Removed: MultiSelect::grouped() is deprecated and is no longer used. Instead the structure of passed options
  determines if it is shown grouped or not.

## 0.8.2 - 2023-09-25

- Added: external files (like vimeo) can now be uploaded via media gallery
- Added: By default, a file field does not have the option to add external files. To allow this,
  set `File::allowExternalFiles()` on the field to select external file links such as Vimeo. Note that at least one
  external driver must be active as well.
- Added: Select field can now accept nested options in following
  syntax: `['value' => 'id', 'label' => 'First product'']`.
- Added: File field can have custom asset type via `File::assetType()`.
- Added: parameter to `FilterPresets::state()` and `FilterPresets::simpleState()` to use different column name,
- Added: `PageResource::showIndexOptionsColumn()` to show edit and options dropdown for each table row. Defaults to
  true. Instead of default `current_state`.
- Added: `PageResource::getNestableNodeLabels()` to provide custom labels for nestable index nodes.
- Added: `PageResource::getIndexDescription()` method to provide custom description to index hero.
- Changed: assetlibrary package to 0.9.2
- Changed: CrudAssistant is now split up in three separate traits: Index-, Create- and EditAssistant.
- Changed: Parent dropdown for nestable pages is now included on create page by default.
- Changed: FilterPresets::simpleState() returns a select field instead of radio field.
- Fixed: PreviewFile on asset pages now uses Asset::getUrl() instead of Media::getUrl() so custom urls are in effect
- Fixed: Enter on media gallery search no longer triggers form submit (of underlying form)
- Removed: slim scripts and css

## 0.8.1 - 2023-09-05

- Fixed: default asset type in migration set to correct string 'default'.
- Fixed: asset fields were saved but not displayed in input fields
- Fixed: nested hotspot fields were added to input fields as array

## 0.8.0 - 2023-08-31

In this release we have refactored and improved the file asset management.

- The Asset library package has been updated to a new major version.
- File handling is done via Livewire components and replaces all the prior vue components.
- The Slim vendor package is no longer in use and replaced by custom Livewire components. This resulted in less code
  complexity and easier upload
  handling.

### Added

- `Field::requiredFallbackLocale()` as a alias of `Field::rules('requireFallbackLocale)`.
- callback as fourth optional argument for the Select::sync() method that is called after syncing values.
- `TableColumnLink::target() to add a target attribute to the link. Defaults to `\_self`.
- `MemoizedMysqlNestableRepository` next to the existing `MysqlNestableRepository`.

### Changed

- Upgraded `thinktomorrow/assetlibrary` dependency to `0.9`.
- Default NestableRepository class is now the Memoized repository.
- Resource::getInstanceAttributes is now an array of arguments. Before the array was considered to be the
  eloquent model attributes, but now it can be any number of model dependencies. Any existing setup should be revised.
  Solution is to add an extra array such as: `return ['title' => 'foobar'']` should
  become `return [ ['title' => 'foobar'] ]`.
- Chief admin toast is refactored as a Chief plugin. This introduces a breaking change in frontend site
  behaviour. Please refer to its readme file for the frontend setup.
- Updated `<x-chief::nav/>` layout.
- `<x-chief::nav/>` now has a seperate label attribute. This way you can both display a nav title and label.

### Removed

- Removed `Thinktomorrow\Chief\Forms\Fields\Media\MediaType` as a legacy reference to predefined file keys.
- Removed `Thinktomorrow\Chief\Forms\Fields\Media\FileDTO` since now `Thinktomorrow\AssetLibrary\Asset` is passed to
  file field instead.
-

Removed `Thinktomorrow\Chief\Managers\Assistants\FileUploadAssistant`, `Thinktomorrow\Chief\Managers\Assistants\SlimImageUploadAssistant`
and `Thinktomorrow\Chief\Managers\Assistants\RedactorFileUploadAssistant`. These have been replaced by the
Livewire FileComponent upload logic. The latter one was no longer actively used.

- Removed: `chief-site` view reference. This was only used for the admin toast, which is now its own plugin.

### Fixed

- Issue when projecting menu data on deleting owning mode
- Issue where in certain cases multiselect value in index table filtering wasn't used in query.

## 0.7.25 - 2023-05-24

- Fixed: Only load tags index component when model is taggable.
- Changed: Bump limit of shareable fragments to 30.

## 0.7.24 - 2023-05-23

- Fixed: Testsuite
- Added: Plugins Taggable and TimeTable. Currently still in chief core.
- Added: Pagetitle can be shown with svg icons in table index

## 0.7.23 - 2023-04-19

- Added: A Time field to enter hour values. `\Thinktomorrow\Chief\Forms\Fields\Time::class`.
- Added: Pagetitle can be shown with svg icons in table index
- Fixed: Testsuite

## 0.7.23 - 2023-04-19

- Added: MediaGalleryController now accepts a conversion query defining which conversion should be returned
- Added: you can now find the current Chief version on the bottom of the sidebar navigation view
- Fixed: field-in-window flexbox items are sometimes overflowing the container (e.g. repeat field window view)
- Fixed: RadioFilter and CheckboxFilter field labels were not correctly styled
- Fixed: Checkbox form-input-toggle doesn't shrink anymore in flexbox with long label
- Fixed: charactercount was not triggered in forms on initial page DOM, only in sidebar.

## 0.7.22 - 2023-03-13

- Fixed: Redactor field was not initialized in inserted repeat section
- Fixed: clicking on the login remember password form label doesn't check the corresponding checkbox
- Fixed: NestableFormPresets parent_id MultiSelect is shown on create

## 0.7.21 - 2023-03-08

- Added: `PageResource::getIndexHeaderContent()` method to provide custom content on index header.
- Fixed: MediaGallery images were not showing up in the expected row layout
- Fixed: fragment titles in fragment-select-existing sidebar had h1 styling
- Fixed: bug where model has other primary than default id column
- Fixed: detaching asset with entity id being uuid failed (upgraded assetlibrary to 0.8.6)

## 0.7.20 - 2023-02-21

- Fixed: issue where a new nestable model retrieved all others as descendants
- Changed: you can now prepend or append content to a nav component by using the default slot attribute

## 0.7.19 - 2023-02-13

- Added: `<x-chief::page.layout/>`, `<x-chief::page.template/>`, `<x-chief::page.hero/>` and `<x-chief::page.grid/>`
  components
- Added: `<x-chief::solo.layout/>` and `<x-chief::solo.template/>` components
- Added: `<x-chief::mail.layout/>` and `<x-chief::mail.template/>` components
- Added: `PageAdminConfig::getResponseNotification` as interface method.
- Added: chief::input components
- Fixed: menu item create breadcrumb now links back to correct menu
- Changed: Refactored and changed nestable logic. This introduces a couple of breaking changes. A nestable model should
  now implement the `Nestable` interface.
- Changed: rebuilt all Chief pages with new `<x-chief::page.template/>`.
- Changed: rebuilt all Chief solo pages with new `<x-chief::solo.template/>`.
- Changed: rebuilt all Chief mails with new `<x-chief::mail.template/>`.
- Changed: nav-project view moved to resources/views/templates/page/nav/nav-project.blade.php
- Changed: replaced all input elements with new input components
- Changed: renamed chief-window to use default component namespace chief::window
- Changed: renamed chief-inline-notifications to use default component namespace chief::inline-notifications
- Changed: renamed chief-icon-label to use default component namespace chief::icon-label
- Changed: renamed chief::icon-button to use default component namespace chief::icon-button
- Changed: renamed chief-hierarchy to use default component namespace chief::hierarchy
- Deprecated: Publishable trait. The same functionality can be achieved with the UsesPageState trait.
- Removed: chief-form::formgroup component (use new input components instead)
- Removed: chief-form::formgroup.window component
- Removed: chief-form::formgroup.error component (use new chief::input.error component instead)
- Removed: chief-form::formgroup.prepend-append component (use new chief::input.prepend-append component instead)
- Removed: global form element styling (e.g. input, .prepend, .append, .select, .with-toggle...)

## 0.7.18 - 2023-01-23

- Added: show options menu on page index. Options show links to duplicate and state changes
- Added: Edit and preview link on page index.
- Added: option to the fallback locale on a fragment. You can do this `Fragmentable::dynamicLocaleFallback`.
- Added: option to set the admin locale. This is the locale in which to display page content by default.
- Changed: updated node packages to their latest stable version
- Fixed: when the localized repeatfield values were not present, the entire array was given as value instead of the
  default value.
- Fixed: issue where archiving or deleting a parent node, resulted in a failed node collection retrieval, due to the
  strict setting.
- Fixed: issue when retrieving the baseUrlSegment resulted in an error when the parent node was archived/deleted.

## 0.7.17 - 2022-12-13

- Added: nestable page logic
- Added: option to set default for admin filter fields.
- Added: scopeOnline method to stateful contracts. This allows to use it as eloquent query scope.
  E.g. `static::online()->get()`.
- Fixed: title of inline nav is now displayed as section title instead of not showing at all.
- Fixed: Mysql column conflict (`current_state` is ambiguous) when using archivable scope in custom joins.

## 0.7.16 - 2022-12-13

- Added: import redirect script. Run as `php artisan chief:import-redirects <csv>`.

## 0.7.15 - 2022-12-02

- Fixed: honour order of selected options in window
- Changed: Show offline fragments when admin views page in preview mode.

## 0.7.14 - 2022-11-03

- Fixed: FilterPresets::text can now accept jsonColumn with specific table prefix, e.g. orders.data
- Fixed: sorting on table index
- Changed: BC! Field and Table views are now set via `setView($view)` and no longer via `view($view)`. Since Laravel
  9.36 component abstract has an own view() method.
- Changed: Icon set updated. Some project specific icons might be missing.
- Added: Table index view. Set getIndexViewType() on your resource to 'table'.
- Added: TableResource interface to provide the required methods for a table view.
- Added: HasToggleDisplay Trait for Checkbox fields

## 0.7.13 - 2022-09-28

- Fixed: Don't require sidebar prop on the chief::index view component.
- Fixed: application errors via chief response are now properly reported.

## 0.7.12 - 2022-09-21

- Fixed: adding homepage as menuitem resulted in menuitem without url.

## 0.7.11 - 2022-09-20

- Fixed: sorting via sorting-index didnt trigger sorting endpoint
- Changed: Nav menu title now shows the client name as set in `config.app.client`. Defaults to 'Chief'.
- Changed: Nav vertically spaced a bit better. To add a hr in your nav, add `<hr class="my-6 border-grey-100">`.

## 0.7.10 - 2022-09-07

- Fixed: issue with sidebar trigger when using nested forms
- Fixed: adding existing fragment showed json response instead of redirect
- Fixed: issue where deleting model redirected to the index inside the sidebar.
- Fixed: sorting logic can now handle uuids, rather than only integers.
- Fixed: double clicks sometimes opened up sidebar with duplicate content.
- Fixed: adding file on create page didn't upload.
- Fixed: multiselect for key value pairs
- Fixed: locale showed up in validation errors when there were no multiple localized fields.
- Fixed: nested fragment broke when adding fragmentowner as nested fragment
- Changed: FilterPresets::text() now expects the queryParameter as first parameter. Beforehand the first parameter was
  the array of dynamic attributes.
- Changed: Field::options callback now has the model as second parameter.
- Changed: Default field value is no longer showed in field window views.
- Added: option to choose where to go to after creating model. Via `Resource::getRedirectAfterCreate()`. This can be set
  to null as well aka when used in sidebar.
- Added: resource::getInstanceAttributes method to set default attributes on a model when record is not created yet
- Added: The data-sortable-id-type can now be set on the sortable container. It defaults to an int but now you set it to
  string so you can use an uuid as sortable id as well.
- Added: Sorted event after sorted

## 0.7.9 - 2022-07-25

- Fixed: issue where removing diacritics in url removed entire url entry e.g. foobér => foobar.
- Fixed: issue where ordering nested fragments gave an error.

## 0.7.8 - 2022-06-15

- Fixed: issue where deleting model redirected to the index inside the sidebar.

## 0.7.7 - 2022-05-17

- Fixed: Trigger PageChanged event when fragments are sorted.
- Fixed: stay in sidebar if redirect is set for a fragment. By default a fragment with nested fragments redirects after
  creation to its edit page.
- Fixed: issue where file previews didn't show the original image while queue was still processing conversions.

## 0.7.6 - 2022-05-11

- Added: Simple state to use when your model only needs: online, offline and delete states. Add the `UsesSimpleState`
  trait.

## 0.7.5 - 2022-05-10

- Added: PageChanged event that can notify of any changed pages. Can be used for breaking cache.

## 0.7.4 - 2022-05-09

- Fixed: Filter presets where page state enum was used as option key.
- Fixed: Toast on error page fetches invalid url.
- Added: `StateAdminConfig::getTransitionFields` so you can add form fields that need to be filled in when changing
  state

## 0.7.2 - 2022-04-29

- Fixed: preventing double submits when clicking fast on fragment create
- Added: allow for developer to disable field require validation. Add the `CHIEF_DISABLE_FIELD_REQUIRED_VALIDATION=true`
  to your .env
- Added: easier way to add custom state config. This allows for easier custom state flows in projects.
- Changed: Resource keys must be unique. Trying to register a duplicate resource now halts registration.
- Removed: StatusAssistant. If you have added this trait in your project, replace it with the new `StateAssistant`.

## 0.7.1 - 2022-04-21

- Fixed: dashed squanto files are now displayed in admin without dash. This also fixes an issue with not showing
  translations when using dashed filenames.
- Fixed: Preview layout of images and grid.
- Added: Nested repeats. Now a repeat field can contain a repeat field itself.
- Added: A resource can set a custom `SaveFields` class.

## 0.7.0 - 2022-04-08

This release brings a major refactor of the forms and fields api. Its aim is to ease the layout setup of admin forms and
simplify the logic around its view composition.

### Impactful

- Menu items has been optimized for performance. After migrations you should run `php artisan chief:project-menu`. This
  will project model data on each menuitem.
- Removed: extract dynamic fields based on field definitions.
- Removed: chiefRegister()->model() is removed. Use chiefRegister()->resource().
- Changed: Each viewable model needs to have a `viewKey()` method to determine the frontend view path.
- Changed: Nav tags are no longer added via the register method, chiefRegister()->resource(). Instead on the page
  resource you should set the `protected function getNavTags(): array` method to provide the nav tags.
- Changed: chief admin toast is no longer accessible via `chief::site.admin-toast`. It should now be referred to
  via: `chief-site::admin-toast`.

- Changed: Restructured Fields files and classes under a dedicated `src/Forms` directory. Rename all your field
  namespaces from `Thinktomorrow\Chief\ManagedModels\Fields\Types\<Field>`
  to `Thinktomorrow\Chief\Forms\Fields\<Field>`.
- Removed: field method `customSaveMethod`. Replace any occurrences with the save() method. See below.
- Removed: magic model methods for saving a field. Replace any `save<FIELDKEY>Field()` and `save<FIELDTYPE>Fields()`
  methods on your model. View next item how to do this:
- Changed: Because these methods are removed, A custom save for a field can now only be set via a Closure passed via the
  Field::save(Closure) method. e.g. Field::save(fn($model, $field, $input, $files) => $this->customSave())
- Changed: `InputField` is renamed to `TextInput`.
- Changed: Field rules method only accepts an array. A pipe separated string is no longer valid, e.g. email|max:100
  should now be passed as ['email','max:100].
- Changed: `Thinktomorrow\Chief\Managers\Assistants\FieldsComponentAssistant`
  to `Thinktomorrow\Chief\Managers\Assistants\FormsAssistant`.
- Removed: custom sidebar form views are removed. These are the methods ending with `*FieldsAdminView()`. You can now
  add a custom view to a form instead.
- Removed: `selected()` method for option fields such as select, checkbox or radio. Use `value()` instead.
- Removed: `notOnCreate()` method. Use `tag('not-on-create')` instead.
- Removed: Fragment views no longer have owner specific view paths. Fragment has only one view. Less magic.

### Keep an eye out for possible impact

- Changed: Validation of localized fields will now validate each localized entry. Before if an entire locale entry array
  was empty, it was regarded as a request input and the validation on these locales would get skipped. This is no longer
  the case.
- Changed: If you set required on localized field, every locale is required. To only require the default language, you
  can use a special designed validation rule called `requiredFallbackLocale`.
- Changed: Namespace of Media classes has been moved from `Thinktomorrow\Chief\ManagedModels\Media`
  to `Thinktomorrow\Chief\Forms\Fields\Media`.

### Probably not so impactful

- Changed: `Field::getColumn()` has been renamed to `Field::getColumnName()`.
- Changed: `Field::required()` has been renamed to `Field::isRequired()`.
- Changed: `fields-edit` route to `form-edit`.
- Changed: `fields-update` route to `form-update`.
- Removed: `saveFields` is removed from the ManagedModel interface. Saving fields is done via a dedicated class and no
  longer via the model->saveFields() method.
- Removed: `Field::optional()`
- Removed: `Field::getType()`.
- Removed: The FieldType class and all its definitions which are not useful in the new setup.
