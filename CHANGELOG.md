# Changelog

All notable changes to the `chief` application template will be documented in this file. Updates should follow
the [Keep a CHANGELOG](http://keepachangelog.com/)
principles.

## Unreleased

-   Removed: Some icons in the main symbols file weren't in use anymore and are therefore removed from Chief. This might cause some project specific icons to not show up anymore, e.g. `#icon-rectangle-group` in project nav files.

### Table component

-   Removed `Resource::getIndexViewType` is no longer used. From now on only one index view is used instead of the former options (index and table)
-   Removed `Resource::getArchivedIndexView` is no longer used. The index view is used for the archived models as well.
-   Removed `PageResource::showIndexSidebarAside()` is no longer used and removed from the interface. 
-   Removed `PageResource::getIndexPagination()()` is no longer used and removed. Use the `Table::paginate()` method instead which defaults to 20 per page. 
-   Removed `PageResource::showIndexOptionsColumn()` to show edit and options dropdown for each table row. Now set the row actions via the `Table::rowActions()` method.
-   Removed `PageResource::getIndexCardView()`, `PageResource::getIndexCardTitle(()` and `PageResource::getIndexCardContent(()` to show the index as cards. This is no longer used as table is the only overview layout.
- Removed `TableResource` interface and `TableResourceDefault` trait and methods. Any implementations of these methods should be replaced by a Table method.
    -   `getTableRow` defined the columns. This is now done via `Table::columns()`.
    -   `getTableRowActions` defined the actions. This is now done via `Table::rowActions()`.
    -   `displayTableHeaderAsSticky` option to set table header sticky is no longer used.
    -   Tags plugin repository has two new methods: `Repository::attachTags(string $ownerType, array $ownerIds, array $tagIds)` and `Repository::detachTags(string $ownerType, array $ownerIds, array $tagIds)`.
    -   Tags plugin repository API for syncing tags has changed. Usage is now as: `Repository::syncTags(string $ownerType, array $ownerIds, array $tagIds)`.

Some documentation:

-   Table can have a query closure, collection rows or resource as data source.

### Improved and simplified tree retrieval

-   Add the `\Thinktomorrow\Chief\Resource\TreeResource` interface to your resource to use the new two methods straight from the Resource. These methods are: `getTreeModelIds` and `getTreeModels`.
-   Removed `IndexRepository` interface and default implementation. `IndexRepository` is no longer used.
-   Removed `NestableRepository` interface and default implementation. `NestableRepository` is no longer used.
-   Removed `Resource::indexRepository()`. No longer in use by new Table component.
-   Removed `Resource::getNestableNodeLabels()`. No longer in use by new Table component.

### Improved and simplified Menu tree retrieval

Menu tree retrieval is now simpler. All the node data is available on the Menu item model. 
This should not impose a breaking change but you should check your implementation to see if it still works as expected.

-   Removed `MenuItemNode`, `MenuSource` and `ChiefMenuFactory` classes. These are no longer used.

### General improvements

-   Changed: Extracted partial logic from `StateAssistant` to a `UpdateState` as an action for reuse between commands.
-   Changed: Exports are now stored in the `storage/app/exports` folder.
-   Fixed: isolate all throwables while exporting all resources.

## 0.8.22 - 2024-07-30

-   Fixed: Issue where asset field values weren't saved on asset creation.

## 0.8.21 - 2024-07-29

-   Fixed: Import issue where text import of non-string values, like numbers would break squanto translations.
-   Fixed: Import issue where menu headers are not labeled as expected. Now throws an error on import.

## 0.8.20 - 2024-07-22

-   Fixed: Could not save slug containing slashes on a nestable page.
-   Added: Export plugin to export and import text translations for page resources, menu and static squanto content.

## 0.8.19 - 2024-06-06

-   Upgraded `thinktomorrow\url` dependency to `3.0.0`.
-   Fixed: issue where repeat field would not work on create of fragment with custom dependencies.
-   Fixed: multiple select filter options were not all used as filter in the query, only the first one.
-   Fixed: Editor scripts were not loaded on the create page. Now the Html field works as intended on create.
-   Added: `src/Forms/Layouts/Card` HasCollapsible trait to toggle content visibility.
-   Added: support for custom locale segment in the chief admin toast.

## 0.8.17 - 2024-04-10

-   Fixed: odd livewire error 'Uncaught Snapshot missing on Livewire component' due to dialog DOM position?
-   Fixed: mailto or tel links can now be added as menu item links.
-   Added: Menuitem db morph added as `menuitem`. Currently only used by activity log.

## 0.8.16 - 2024-03-28

-   Changed: Menu item tree changed to keep empty label and not provide a default label. `MenuItemNode::getLabel()` returns the label as entered for the given menu and null if not entered. To keep using this fallback behaviour, you can use the `MenuItemNode::getAnyLabel()` method.
-   Changed: `MenuItem::getAdminUrlLabel()` -> `MenuItem::getOwnerLabel()` and only gives owner label if present. It no longer returns 'geen link' or the url if owner label was not present.

## 0.8.15 - 2024-03-14

-   **Breaking change**: User class is replaced in database by `chiefuser`key. **This requires you to run migrations!**
-   Upgraded `livewire/livewire` dependency to `3.4.6`.
-   Fixed: cancelling a file edit broke alpine when the dialog was reopened
-   Fixed: Storing localized values on an unsaved asset https://github.com/thinktomorrow/chief/commit/0221d47ab
-   Fixed: Now custom Asset fields are editable on an unsaved asset.
-   Fixed: Livewire upgrade to 3.4.6
-   Fixed: Clipboard script was loaded for file without link
-   Fixed: Custom asset fields on fragment could not be saved.
-   Fixed: Some file metadata (width, height) was missing in file window.
-   Added: Tags to eager loading on the index results.

## 0.8.14 - 2024-02-20

-   Fixed: dependency livewire fixed to 3.4.4 because of rescan missing since 3.4.5
-   Fixed: sorted order in index was trumped by published_at sorting

## 0.8.13 - 2024-02-15

-   Fixed: The same asset can now be attached to the same model in different locales.
-   Fixed: last table index header filter had no defined width.
-   Added: In media gallery clicking on a media in current selection now deselects the media asset.
-   Added: Copy bookmark button in fragment list.
-   Changed: Links window urls are now opening in the same window by default.
-   Removed: copy-to-clipboard script in favor of copy-button component.

## 0.8.12 - 2024-01-23

-   Fixed: livewire scripts on old page template was not instantiated correctly.
-   Added: Fragmentable::beforeCreate() callback to alter fragment before the create view.

## 0.8.11 - 2024-01-16

-   Fixed: display of duplicate options on edit.
-   Fixed: index options dropdown based on key named different from id.
-   Fixed: use default updated_at sorting only when no other sorting is applied.
-   Fixed: create tag link was not available after first tag was created.

## 0.8.10 - 2024-01-10

-   Fixed: if field disallowed external files via allowExternalFiles(false), external files were still visible and selectable in the media gallery modal.
-   Changed: Duplicate ux improved

## 0.8.9 - 2023-12-20

-   Changed: Move duplicate action per index item to the create action on the index. This will improve index pageload
-   Changed: TimeTable FieldPresets::timetable now returns a Form component instead of an iterable.
-   Removed: State options from index page (such as put online / offline / archive) to improve index pageload

## 0.8.8 - 2023-11-22

-   Fixed: issue where model could not attach same asset for different fields.

## 0.8.7 - 2023-11-22

-   Added: `FilterPresets::attribute()` for search on column, dynamic key or relation column.
-   Fixed: issue where no more than one hotspot could be added at the same time
-   Deprecated: `FilterPresets::text()` and `FilterPresets::column()` in favor of the `FilterPresets::attribute()` function.

## 0.8.6 - 2023-11-15

-   Added: File::allowLocalFiles(false) to disallow local uploads or selection. This can be used with File::allowExternalFiles(true) to only allow external files.
-   Fixed: Archive modal bug where redirect options were not displayed due to old options format.
-   Fixed: Bug where replacing media had unexpected results when updating other files afterwards.
-   Fixed: Asset timestamp was not updated when replacing media.

## 0.8.5 - 2023-10-30

-   Added: ExternalFiles Youtube plugin.
-   Fixed: Issue where isolating asset without pivot data would break.
-   Fixed: Internal pages multiselect in menu item edit didn't show results.

## 0.8.4 - 2023-10-30

-   Added: Assets used in multiple relations are shared by default. There is now info on where the asset is used in the file edit dialog.
-   Added: An option in the file edit dialog to isolate such a shared asset - and duplicate it - in order to edit solely for given relation.
-   Added: `Select::syncInOrder()` to force synced values in given order.
-   Fixed: Sorting of select options sync values was not kept in given order.
-   Fixed: Sorting behavior on asset files was buggy.
-   Fixed: Buggy update listeners in asset gallery component.
-   Fixed: animated gifs and svg images are no longer converted when uploaded. Here the original sources will always be used instead.
-   Fixed: Conditional fields were not visible on page/sidebar load when based on the (new) MultiSelect.
-   Fixed: Conditional fields logic was initiated x times every form on the page. Now it is only loaded once.
-   Changed: upgraded `thinktomorrow/assetlibrary` to 0.9.5.

## 0.8.3 - 2023-10-24

-   Changed: All Vue components are rebuilt using Blade components, alpine and Livewire.
-   Changed: 'custom-scripts-after-vue' stack is now obsolete, but still supported. Use 'custom-scripts' instead.
-   Fixed: If multiselect sync() was set up, the options of nestable would not show with the breadcrumbs in the select
    options.
-   Fixed: Roles selection on admin invite form was not stored as array.
-   Removed: Vue
-   Removed: MultiSelect::grouped() is deprecated and is no longer used. Instead the structure of passed options
    determines if it is shown grouped or not.

## 0.8.2 - 2023-09-25

-   Added: external files (like vimeo) can now be uploaded via media gallery
-   Added: By default, a file field does not have the option to add external files. To allow this,
    set `File::allowExternalFiles()` on the field to select external file links such as Vimeo. Note that at least one
    external driver must be active as well.
-   Added: Select field can now accept nested options in following
    syntax: `['value' => 'id', 'label' => 'First product'']`.
-   Added: File field can have custom asset type via `File::assetType()`.
-   Added: parameter to `FilterPresets::state()` and `FilterPresets::simpleState()` to use different column name,
-   Added: `PageResource::showIndexOptionsColumn()` to show edit and options dropdown for each table row. Defaults to
    true. Instead of default `current_state`.
-   Added: `PageResource::getNestableNodeLabels()` to provide custom labels for nestable index nodes.
-   Added: `PageResource::getIndexDescription()` method to provide custom description to index hero.
-   Changed: assetlibrary package to 0.9.2
-   Changed: CrudAssistant is now split up in three separate traits: Index-, Create- and EditAssistant.
-   Changed: Parent dropdown for nestable pages is now included on create page by default.
-   Changed: FilterPresets::simpleState() returns a select field instead of radio field.
-   Fixed: PreviewFile on asset pages now uses Asset::getUrl() instead of Media::getUrl() so custom urls are in effect
-   Fixed: Enter on media gallery search no longer triggers form submit (of underlying form)
-   Removed: slim scripts and css

## 0.8.1 - 2023-09-05

-   Fixed: default asset type in migration set to correct string 'default'.
-   Fixed: asset fields were saved but not displayed in input fields
-   Fixed: nested hotspot fields were added to input fields as array

## 0.8.0 - 2023-08-31

In this release we have refactored and improved the file asset management.

-   The Asset library package has been updated to a new major version.
-   File handling is done via Livewire components and replaces all the prior vue components.
-   The Slim vendor package is no longer in use and replaced by custom Livewire components. This resulted in less code
    complexity and easier upload
    handling.

### Added

-   `Field::requiredFallbackLocale()` as a alias of `Field::rules('requireFallbackLocale)`.
-   callback as fourth optional argument for the Select::sync() method that is called after syncing values.
-   `TableColumnLink::target() to add a target attribute to the link. Defaults to `\_self`.
-   `MemoizedMysqlNestableRepository` next to the existing `MysqlNestableRepository`.

### Changed

-   Upgraded `thinktomorrow/assetlibrary` dependency to `0.9`.
-   Default NestableRepository class is now the Memoized repository.
-   Resource::getInstanceAttributes is now an array of arguments. Before the array was considered to be the
    eloquent model attributes, but now it can be any number of model dependencies. Any existing setup should be revised.
    Solution is to add an extra array such as: `return ['title' => 'foobar'']` should
    become `return [ ['title' => 'foobar'] ]`.
-   Chief admin toast is refactored as a Chief plugin. This introduces a breaking change in frontend site
    behaviour. Please refer to its readme file for the frontend setup.
-   Updated `<x-chief::nav/>` layout.
-   `<x-chief::nav/>` now has a seperate label attribute. This way you can both display a nav title and label.

### Removed

-   Removed `Thinktomorrow\Chief\Forms\Fields\Media\MediaType` as a legacy reference to predefined file keys.
-   Removed `Thinktomorrow\Chief\Forms\Fields\Media\FileDTO` since now `Thinktomorrow\AssetLibrary\Asset` is passed to
    file field instead.
-

Removed `Thinktomorrow\Chief\Managers\Assistants\FileUploadAssistant`, `Thinktomorrow\Chief\Managers\Assistants\SlimImageUploadAssistant`
and `Thinktomorrow\Chief\Managers\Assistants\RedactorFileUploadAssistant`. These have been replaced by the
Livewire FileComponent upload logic. The latter one was no longer actively used.

-   Removed: `chief-site` view reference. This was only used for the admin toast, which is now its own plugin.

### Fixed

-   Issue when projecting menu data on deleting owning mode
-   Issue where in certain cases multiselect value in index table filtering wasn't used in query.
