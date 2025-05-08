# Changelog

All notable changes to the `chief` application template will be documented in this file. Updates should follow
the [Keep a CHANGELOG](http://keepachangelog.com/)
principles.

## 2025-03-06 - 0.9.2

- Fixed: custom attributes on checkbox and radio fields were not rendered.

## 2025-02-18 - 0.9.1

- Fixed: breadcrumbs were not rendered in select dropdown for nested resources.
- Fixed: Ancestor sequence was not respected on NestableDefault::getAncestors() method.
- Fixed: Ordering columns with uppercase keys were not ordered correctly.
- Fixed: Redactor toolbar z-index issue where the toolbar was overlapping multiselect dropdowns.
- Changed: Bump multiselect search results to 20 instead of 10 so more matches are visible.
- Changed: Mimetype icons are now more consistent. Also added new icons for `.xls`, `.mp3` and `.csv` files.

## 2025-01-06 - 0.9.0

- Added: new set of UI components, originating from the new table component.
- Added: new table component for index views. This replaces old index views and related resource methods.
- Changed: minimum PHP version required is 8.2.
- Changed: `thinktomorrow/vine` dependency to `0.5.*`. This will affect menu retrieval in your project files.
- Changed: Tree retrieval. Nestable logic, classes and tree retrieval has changed. All nestable resources and models,
  like Page, need to be adjusted.

### General

- Removed: Some icons in the main symbols file weren't in use anymore and are therefore removed from Chief. This might
  cause some project specific icons to not show up anymore, e.g. `#icon-rectangle-group` in project nav files.
- Changed: Extracted partial logic from `StateAssistant` to a `UpdateState` as an action for reuse between commands.

### A new Table component

- Removed `Resource::getIndexViewType` is no longer used. From now on only one index view is used instead of the former
  options (index and table)
- Removed `Resource::getArchivedIndexView` is no longer used. The index view is used for the archived models as well.
- Removed `PageResource::showIndexSidebarAside()` is no longer used and removed from the interface.
- Removed `PageResource::getIndexPagination()()` is no longer used and removed. Use the `Table::paginate()` method
  instead which defaults to 20 per page.
- Removed `PageResource::showIndexOptionsColumn()` to show edit and options dropdown for each table row. Now set the row
  actions via the `Table::rowActions()` method.
- Removed `PageResource::getIndexCardView()`, `PageResource::getIndexCardTitle(()` and
  `PageResource::getIndexCardContent(()` to show the index as cards. This is no longer used as table is the only
  overview layout.
- Removed `TableResource` interface and `TableResourceDefault` trait and methods. Any implementations of these methods
  should be replaced by a Table method.
    - `getTableRow` defined the columns. This is now done via `Table::columns()`.
    - `getTableRowActions` defined the actions. This is now done via `Table::rowActions()`.
    - `displayTableHeaderAsSticky` option to set table header sticky is no longer used.
    - Tags plugin repository has two new methods:
      `Repository::attachTags(string $ownerType, array $ownerIds, array $tagIds)` and
      `Repository::detachTags(string $ownerType, array $ownerIds, array $tagIds)`.
    - Tags plugin repository API for syncing tags has changed. Usage is now as:
      `Repository::syncTags(string $ownerType, array $ownerIds, array $tagIds)`.

### Tree retrieval

The nestable logic has been simplified. The vine package is updated and the Chief tree retrieval has been updated
accordingly.

- Any nestable resource need to adjust it's tree retrieval traits and interface. It should look something like this:

```php
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\PageDefaultWithNestableUrl;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableDefault;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Nestable;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Actions\NestableFormPresets;
use ...

class Page extends Model implements PageContract, PageResource, Nestable
{
    use PageResourceDefault;
    use PageDefaultWithNestableUrl;
    use NestableDefault;

    ...
```

The Resource for the tree should now also implement the `TreeResource` interface.

```php
use Thinktomorrow\Chief\Resource\TreeResource;
use Thinktomorrow\Chief\Resource\TreeResourceDefault;
use ...

class Category implements TreeResource
{
    use TreeResourceDefault
    ...
```

- Add the `\Thinktomorrow\Chief\Resource\TreeResource` interface to your resource to use the new two methods straight
  from the Resource. These methods are: `getTreeModelIds` and `getTreeModels`.
- Moved and renamed `Thinktomorrow\Chief\Shared\Concerns\Nestable\Page\NestablePageDefault` to
  `Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\PageDefaultWithNestableUrl`.
- Moved `Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable` to
  `Thinktomorrow\Chief\Shared\Concerns\Nestable\Nestable`.
- Moved `Thinktomorrow\Chief\Shared\Concerns\Nestable\Form\NestableFormPresets` to
  `Thinktomorrow\Chief\Shared\Concerns\Nestable\Actions\NestableFormPresets`.
- Removed `IndexRepository` interface and default implementation. `IndexRepository` is no longer used.
- Removed `NestableRepository` interface and default implementation. `NestableRepository` is no longer used.
- Removed `Resource::indexRepository()`. No longer in use by new Table component.
- Removed `Resource::getNestableNodeLabels()`. No longer in use by new Table component.

### Menu

Menu tree retrieval is now simpler. Also due to the change of the underlying Vine package. All the node data is
available on the Menu item model.
This should not impose a breaking change but you should check your implementation to see if it still works as expected.
Some Chief classes are removed because retrieval is now simpler. Removed classes are: `MenuItemNode`, `MenuSource` and
`ChiefMenuFactory`.

### Chief UI components

##### General components

- `x-chief-table::button`: A new and improved button component, ready to be used everywhere in Chief. This will be
  replacing elements with classes such as: `.btn`, `.btn-primary`, `.btn-grey`, ... But also the old `x-chief::button`
  component.
- `x-chief-table::badge`: A component to show text as a badge, ready to be used everywhere in Chief. This will be
  replacing elements with classes such as: `.label`, `.label-xs`, `.label-grey`, ...

##### Dialog components

- `x-chief::dialog`: A new dialog component replacing the old 'modal' dialog component. To be used as an interface for
  any dialog components, like: `x-chief::dialog.modal`, `x-chief::dialog.dropdown`, `x-chief::dialog.sidebar`.
- `x-chief::dialog.modal`: A new modal component. This can be used to replace the old `x-chief::dialog` component.
- `x-chief::dialog.dropdown`: A new dropdown component. This can be used to replace the old `x-chief::dropdown`
  component.
- `x-chief::dialog.drawer`: A new drawer component. In time this will replace the current sidebar edit functionality.

##### Table components

- `x-chief-table::action.button`: A version of the button component specifically for table actions.
- `x-chief-table::action.dropdown.item`: A version of the dropdown item component specifically for table actions.
- `x-chief-table::filter.select`: A table filter select component.
- `x-chief-table::filter.text`: A table filter text component.

##### Icon components

Previously all Chief icons were defined in a single `symbols.blade.php` file. These icons are still available but are
going to be phased out by the new icon components. For example, where you would previously use
`<svg><use xlink:href="#icon-search"></use></svg>`, you can now use `<x-chief::icon.search/>`. All icons have a
`data-slot="icon"` attribute, so you can easily add them to any component and style them accordingly.

#### General styling updates

- Changed: All manager index views are replaced by the all-new table index. Only the old sorting view is still available
  until the new table component sorting is implemented.
- Changed: Some views - like the manager edit view - are now using the new `x-chief-table::button` and
  `x-chief-table::dialog` components. In time, these will be implemented everywhere in Chief.
- Changed: `x-chief::window` has a more compact styling to match the new table component.
- Removed: All old layout views. These only existed to provide a fallback for custom Chief views in older projects.
- Probably so much more...
