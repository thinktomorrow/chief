# Chief 0.10 to 0.11 Replacements

These mappings are derived from the Chief 0.11 worktree. Apply only to project-owned files, longest old token first, and exclude `vendor/`, `node_modules/`, `storage/`, generated cache/coverage directories, `public/chief/build/`, and other hashed or compiled public builds.

## Livewire Aliases

The first table contains all 32 core aliases. Match complete aliases, including namespace and component name.

| Chief 0.10 | Chief 0.11 |
| --- | --- |
| `chief-wire::file-field-choose-external` | `chief-wire-assets::external-file-field-asset-chooser` |
| `chief-wire::file-field-upload` | `chief-wire-assets::file-field-asset-uploader` |
| `chief-wire::file-field-choose` | `chief-wire-assets::file-field-asset-chooser` |
| `chief-wire::file-field-edit` | `chief-wire-assets::file-field-asset-editor` |
| `chief-wire::file-gallery` | `chief-wire-assets::asset-gallery` |
| `chief-wire::file-upload` | `chief-wire-assets::gallery-asset-uploader` |
| `chief-wire::file-edit` | `chief-wire-assets::gallery-asset-editor` |
| `chief-wire::asset-delete` | `chief-wire-assets::asset-delete-dialog` |
| `chief-form::dialog` | `chief-wire-form::action-dialog` |
| `chief-wire::edit-form` | `chief-wire-form::edit-model-form` |
| `chief-wire::repeat` | `chief-wire-form::repeat-field-editor` |
| `chief-wire::form` | `chief-wire-form::model-form` |
| `chief-fragments::add-fragment` | `chief-wire-fragments::add-fragment` |
| `chief-fragments::edit-fragment` | `chief-wire-fragments::edit-fragment` |
| `chief-fragments::contexts` | `chief-wire-fragments::fragment-contexts` |
| `chief-fragments::context` | `chief-wire-fragments::fragment-context` |
| `chief-wire::edit-context` | `chief-wire-fragments::edit-context` |
| `chief-wire::add-context` | `chief-wire-fragments::add-context` |
| `chief-wire::edit-menu` | `chief-wire-menu::edit-menu` |
| `chief-wire::add-menu` | `chief-wire-menu::add-menu` |
| `chief-wire::menus` | `chief-wire-menu::menu-list` |
| `chief-wire::create-model` | `chief-wire-models::create-model` |
| `chief-wire::edit-model` | `chief-wire-models::edit-model` |
| `chief-wire::edit-site-selection` | `chief-wire-sites::edit-model-site-selection` |
| `chief-wire::model-site-toggle` | `chief-wire-sites::model-site-toggle` |
| `chief-wire::site-selection` | `chief-wire-sites::model-site-selection` |
| `chief-wire::site-toggle` | `chief-wire-sites::global-site-toggle` |
| `chief-wire::edit-state` | `chief-wire-states::edit-model-state` |
| `chief-wire::state` | `chief-wire-states::model-state` |
| `chief-wire::table` | `chief-wire-table::data-table` |
| `chief-wire::edit-links` | `chief-wire-urls::edit-model-links` |
| `chief-wire::links` | `chief-wire-urls::model-links` |

Plugin aliases:

| Chief 0.10 | Chief 0.11 |
| --- | --- |
| `chief-wire::hotspots` | `chief-wire-hotspots::hot-spot-editor` |
| `chief-wire::image-crop` | `chief-wire-image-crop::image-cropper` |

The old image-crop alias appeared in both legacy Assets code and the ImageCrop plugin. Use the plugin component in 0.11; do not retain or recreate the removed Assets implementation.

## PHP Classes And Traits

### Assets

| Chief 0.10 | Chief 0.11 |
| --- | --- |
| `Thinktomorrow\Chief\Assets\App\ExternalFiles\FileChooseExternalComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\ExternalFileFieldAssetChooser` |
| `Thinktomorrow\Chief\Assets\Livewire\AssetDeleteComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\AssetDeleteDialog` |
| `Thinktomorrow\Chief\Assets\Livewire\FileFieldChooseComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\FileFieldAssetChooser` |
| `Thinktomorrow\Chief\Assets\Livewire\FileFieldEditComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\FileFieldAssetEditor` |
| `Thinktomorrow\Chief\Assets\Livewire\FileFieldUploadComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\FileFieldAssetUploader` |
| `Thinktomorrow\Chief\Assets\Livewire\FileEditComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\GalleryAssetEditor` |
| `Thinktomorrow\Chief\Assets\Livewire\FileUploadComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\GalleryAssetUploader` |
| `Thinktomorrow\Chief\Assets\Livewire\GalleryComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\AssetGallery` |
| `Thinktomorrow\Chief\Assets\Livewire\HasPreviewFiles` | `Thinktomorrow\Chief\Assets\UI\Livewire\HasPreviewFiles` |
| `Thinktomorrow\Chief\Assets\Livewire\HasSyncedFormInputs` | `Thinktomorrow\Chief\Assets\UI\Livewire\HasSyncedFormInputs` |
| `Thinktomorrow\Chief\Assets\Livewire\PreviewFile` | `Thinktomorrow\Chief\Assets\UI\Livewire\PreviewFile` |

| `Thinktomorrow\Chief\Assets\Livewire\Traits\EmitsToNestables` | `Thinktomorrow\Chief\Assets\UI\Livewire\Traits\EmitsToNestables` |
| `Thinktomorrow\Chief\Assets\Livewire\Traits\FileUploadDefaults` | `Thinktomorrow\Chief\Assets\UI\Livewire\Traits\FileUploadDefaults` |
| `Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithBasename` | `Thinktomorrow\Chief\Assets\UI\Livewire\Traits\InteractsWithBasename` |
| `Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithChoosingAssets` | `Thinktomorrow\Chief\Assets\UI\Livewire\Traits\InteractsWithChoosingAssets` |
| `Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithGroupedForms` | `Thinktomorrow\Chief\Assets\UI\Livewire\Traits\InteractsWithGroupedForms` |
| `Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithGallery` | `Thinktomorrow\Chief\Assets\UI\Livewire\Traits\InteractsWithGallery` |
| `Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithForm` | `Thinktomorrow\Chief\Assets\UI\Livewire\Traits\InteractsWithForm` |
| `Thinktomorrow\Chief\Assets\Livewire\Traits\RenamesErrorBagFileAttribute` | `Thinktomorrow\Chief\Assets\UI\Livewire\Traits\RenamesErrorBagFileAttribute` |
| `Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog` | `Thinktomorrow\Chief\Assets\UI\Livewire\Traits\ShowsAsDialog` |

The complete known Assets trait prefix move is represented above. Do not replace the prefix for an unknown class without checking the installed 0.11 source.

`Thinktomorrow\Chief\Assets\Plugins\ImageCropComponent` was removed. Its replacement behavior belongs to `Thinktomorrow\Chief\Plugins\ImageCrop\UI\Livewire\ImageCropper`; review custom subclasses because this is not a drop-in inheritance guarantee.

### Forms

| Chief 0.10 | Chief 0.11 |
| --- | --- |
| `Thinktomorrow\Chief\Forms\Dialogs\Livewire\TableActionDialogReference` | `Thinktomorrow\Chief\Forms\UI\Livewire\References\TableActionDialogReference` |
| `Thinktomorrow\Chief\Forms\Dialogs\Livewire\DialogReference` | `Thinktomorrow\Chief\Forms\UI\Livewire\References\DialogReference` |
| `Thinktomorrow\Chief\Forms\Dialogs\Livewire\DialogComponent` | `Thinktomorrow\Chief\Forms\UI\Livewire\ActionDialog` |
| `Thinktomorrow\Chief\Forms\UI\Livewire\EditFormComponent` | `Thinktomorrow\Chief\Forms\UI\Livewire\EditModelForm` |
| `Thinktomorrow\Chief\Forms\UI\Livewire\FormComponent` | `Thinktomorrow\Chief\Forms\UI\Livewire\ModelForm` |
| `Thinktomorrow\Chief\Forms\UI\Livewire\RepeatComponent` | `Thinktomorrow\Chief\Forms\UI\Livewire\RepeatFieldEditor` |

### Fragments

| Chief 0.10 | Chief 0.11 |
| --- | --- |
| `Thinktomorrow\Chief\Fragments\UI\Livewire\Context\AddContext` | `Thinktomorrow\Chief\Fragments\UI\Livewire\AddContext` |
| `Thinktomorrow\Chief\Fragments\UI\Livewire\Context\EditContext` | `Thinktomorrow\Chief\Fragments\UI\Livewire\EditContext` |
| `Thinktomorrow\Chief\Fragments\UI\Livewire\Context\Contexts` | `Thinktomorrow\Chief\Fragments\UI\Livewire\FragmentContexts` |
| `Thinktomorrow\Chief\Fragments\UI\Livewire\Context\Context` | `Thinktomorrow\Chief\Fragments\UI\Livewire\FragmentContext` |
| `Thinktomorrow\Chief\Fragments\UI\Livewire\Fragment\AddFragment` | `Thinktomorrow\Chief\Fragments\UI\Livewire\AddFragment` |
| `Thinktomorrow\Chief\Fragments\UI\Livewire\Fragment\EditFragment` | `Thinktomorrow\Chief\Fragments\UI\Livewire\EditFragment` |

The DTO and support namespaces under `Context`, `Fragment`, `TabItems`, and `_partials` did not all move. Replace only the full classes above; do not broadly flatten `Fragments\UI\Livewire\Context\` or `Fragment\`.

### Models, Menu, Sites, States, And Urls

| Chief 0.10 | Chief 0.11 |
| --- | --- |
| `Thinktomorrow\Chief\Models\UI\Livewire\CreateModelComponent` | `Thinktomorrow\Chief\Models\UI\Livewire\CreateModel` |
| `Thinktomorrow\Chief\Models\UI\Livewire\EditModelComponent` | `Thinktomorrow\Chief\Models\UI\Livewire\EditModel` |
| `Thinktomorrow\Chief\Menu\UI\Livewire\Menus` | `Thinktomorrow\Chief\Menu\UI\Livewire\MenuList` |
| `Thinktomorrow\Chief\Sites\UI\Livewire\SiteSelect\EditSiteSelection` | `Thinktomorrow\Chief\Sites\UI\Livewire\EditModelSiteSelection` |
| `Thinktomorrow\Chief\Sites\UI\Livewire\SiteSelect\SiteSelection` | `Thinktomorrow\Chief\Sites\UI\Livewire\ModelSiteSelection` |
| `Thinktomorrow\Chief\Sites\UI\Livewire\SiteToggle\GlobalSiteToggle` | `Thinktomorrow\Chief\Sites\UI\Livewire\GlobalSiteToggle` |
| `Thinktomorrow\Chief\Sites\UI\Livewire\SiteToggle\ModelSiteToggle` | `Thinktomorrow\Chief\Sites\UI\Livewire\ModelSiteToggle` |
| `Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\EditState` | `Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\EditModelState` |
| `Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\State` | `Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\ModelState` |
| `Thinktomorrow\Chief\Urls\UI\Livewire\Links\EditLinks` | `Thinktomorrow\Chief\Urls\UI\Livewire\EditModelLinks` |
| `Thinktomorrow\Chief\Urls\UI\Livewire\Links\Links` | `Thinktomorrow\Chief\Urls\UI\Livewire\ModelLinks` |

URL support types such as `LinkDto`, `LinkUrl`, `WithAddingSites`, and `WithLinks` remain under `Thinktomorrow\Chief\Urls\UI\Livewire\Links`; do not flatten that namespace.

### Table

| Chief 0.10 | Chief 0.11 |
| --- | --- |
| `Thinktomorrow\Chief\Table\Livewire\TableComponent` | `Thinktomorrow\Chief\Table\UI\Livewire\DataTable` |
| `Thinktomorrow\Chief\Table\Livewire\TreeModels` | `Thinktomorrow\Chief\Table\UI\Livewire\TreeModels` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\WithActions` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\WithActions` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\WithBreadcrumbColumn` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\WithBreadcrumbColumn` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\WithBulkActions` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\WithBulkActions` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\WithBulkSelection` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\WithBulkSelection` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\WithColumnSelection` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\WithColumnSelection` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\WithColumns` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\WithColumns` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\WithFilters` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\WithFilters` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\WithItemsPerPageSelection` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\WithItemsPerPageSelection` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\WithNotifications` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\WithNotifications` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\WithPagination` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\WithPagination` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\WithReordering` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\WithReordering` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\WithRowActions` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\WithRowActions` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\WithSorters` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\WithSorters` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\WithTreeResults` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\WithTreeResults` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\WithVariantFilters` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\WithVariantFilters` |

The complete known Table concern prefix move is represented above. Do not replace the prefix for an unknown class without checking the installed 0.11 source.

### Plugins

| Chief 0.10 | Chief 0.11 |
| --- | --- |
| `Thinktomorrow\Chief\Plugins\HotSpots\HotSpotComponent` | `Thinktomorrow\Chief\Plugins\HotSpots\UI\Livewire\HotSpotEditor` |
| `Thinktomorrow\Chief\Plugins\ImageCrop\ImageCropComponent` | `Thinktomorrow\Chief\Plugins\ImageCrop\UI\Livewire\ImageCropper` |

## Published View Overrides

Map a view only after confirming it is a project override of that Chief view. Preserve custom content and merge new behavior manually.

### Assets views

| Chief 0.10 view | Chief 0.11 view |
| --- | --- |
| `chief-assets::livewire.file-choose-external` | `chief-assets::livewire.external-file-field-asset-chooser` |
| `chief-assets::livewire.file-edit-external` | `chief-assets::livewire._partials.file-edit-external` |
| `chief-assets::livewire.file-edit-local` | `chief-assets::livewire._partials.file-edit-local` |
| `chief-assets::gallery-component` | `chief-assets::livewire.asset-gallery` |
| `chief-assets::file-field-choose` | `chief-assets::livewire.file-field-asset-chooser` |
| `chief-assets::file-field-upload` | `chief-assets::livewire.file-field-asset-uploader` |
| `chief-assets::file-upload` | `chief-assets::livewire.gallery-asset-uploader` |
| `chief-assets::file-edit` | context-dependent: `chief-assets::livewire.file-field-asset-editor` or `chief-assets::livewire.gallery-asset-editor` |
| `chief-assets::asset-delete` | `chief-assets::livewire.asset-delete-dialog` |

Assets filesystem overrides move from `App/resources/` to `UI/views/`. The `_partials/`, `components/`, and most basenames remain stable, except `file-selection-buttons.blade.php` moves into `_partials/`, `file-edit.blade.php` becomes `livewire/_partials/file-editor.blade.php`, and Livewire component basenames follow the table above. The old `image-crop.blade.php` has no Assets view replacement; use the ImageCrop plugin view.

Exact Assets package-path moves for published-copy comparison:

| Chief 0.10 package path | Chief 0.11 package path |
| --- | --- |
| `src/Assets/App/resources/_partials/asset-item.blade.php` | `src/Assets/UI/views/_partials/asset-item.blade.php` |
| `src/Assets/App/resources/_partials/file-edit-external-metadata.blade.php` | `src/Assets/UI/views/_partials/file-edit-external-metadata.blade.php` |
| `src/Assets/App/resources/_partials/file-edit-local-metadata.blade.php` | `src/Assets/UI/views/_partials/file-edit-local-metadata.blade.php` |
| `src/Assets/App/resources/_partials/file-edit-owner-action.blade.php` | `src/Assets/UI/views/_partials/file-edit-owner-action.blade.php` |
| `src/Assets/App/resources/_partials/file-edit-owner-info.blade.php` | `src/Assets/UI/views/_partials/file-edit-owner-info.blade.php` |
| `src/Assets/App/resources/_partials/file-edit-preview-url.blade.php` | `src/Assets/UI/views/_partials/file-edit-preview-url.blade.php` |
| `src/Assets/App/resources/_partials/file-edit-site-toggle.blade.php` | `src/Assets/UI/views/_partials/file-edit-site-toggle.blade.php` |
| `src/Assets/App/resources/_partials/preview-item.blade.php` | `src/Assets/UI/views/_partials/preview-item.blade.php` |
| `src/Assets/App/resources/_partials/select-buttons.blade.php` | `src/Assets/UI/views/_partials/select-buttons.blade.php` |
| `src/Assets/App/resources/_partials/select-empty.blade.php` | `src/Assets/UI/views/_partials/select-empty.blade.php` |
| `src/Assets/App/resources/file-selection-buttons.blade.php` | `src/Assets/UI/views/_partials/file-selection-buttons.blade.php` |
| `src/Assets/App/resources/components/gallery.blade.php` | `src/Assets/UI/views/components/gallery.blade.php` |
| `src/Assets/App/resources/components/preview.blade.php` | `src/Assets/UI/views/components/preview.blade.php` |
| `src/Assets/App/resources/components/select.blade.php` | `src/Assets/UI/views/components/select.blade.php` |
| `src/Assets/App/resources/components/upload-and-dropzone.blade.php` | `src/Assets/UI/views/components/upload-and-dropzone.blade.php` |
| `src/Assets/App/resources/livewire/file-edit-external.blade.php` | `src/Assets/UI/views/livewire/_partials/file-edit-external.blade.php` |
| `src/Assets/App/resources/livewire/file-edit-local.blade.php` | `src/Assets/UI/views/livewire/_partials/file-edit-local.blade.php` |
| `src/Assets/App/resources/file-edit.blade.php` | `src/Assets/UI/views/livewire/_partials/file-editor.blade.php` plus separate field/gallery editor wrappers |
| `src/Assets/App/resources/asset-delete.blade.php` | `src/Assets/UI/views/livewire/asset-delete-dialog.blade.php` |
| `src/Assets/App/resources/gallery-component.blade.php` | `src/Assets/UI/views/livewire/asset-gallery.blade.php` |
| `src/Assets/App/resources/livewire/file-choose-external.blade.php` | `src/Assets/UI/views/livewire/external-file-field-asset-chooser.blade.php` |
| `src/Assets/App/resources/file-field-choose.blade.php` | `src/Assets/UI/views/livewire/file-field-asset-chooser.blade.php` |
| `src/Assets/App/resources/file-field-upload.blade.php` | `src/Assets/UI/views/livewire/file-field-asset-uploader.blade.php` |
| `src/Assets/App/resources/file-upload.blade.php` | `src/Assets/UI/views/livewire/gallery-asset-uploader.blade.php` |

### Other views

| Chief 0.10 view | Chief 0.11 view |
| --- | --- |
| `chief-form::livewire.modal` | `chief-form::livewire.action-dialog-modal` |
| `chief-form::livewire.drawer` | `chief-form::livewire.action-dialog-drawer` |
| `chief-form::livewire.edit-form` | `chief-form::livewire.edit-model-form` |
| `chief-form::livewire.form-compact` | `chief-form::livewire.model-form-compact` |
| `chief-form::livewire.form-inline` | No direct replacement; inline display no longer has a dedicated package view. Review the override and choose `model-form` or a project-owned view. |
| `chief-form::livewire.form` | `chief-form::livewire.model-form` |
| `chief-form::livewire.repeat` | `chief-form::livewire.repeat-field-editor` |
| `chief-fragments::livewire.contexts` | `chief-fragments::livewire.fragment-contexts` |
| `chief-fragments::livewire.context` | `chief-fragments::livewire.fragment-context` |
| `chief-menu::livewire.menus` | `chief-menu::livewire.menu-list` |
| `chief-states::edit-state-callouts` | `chief-states::livewire._partials.edit-model-state-callouts` |
| `chief-states::edit-state-confirm` | `chief-states::livewire._partials.edit-model-state-confirm` |
| `chief-states::edit-state` | `chief-states::livewire.edit-model-state` |
| `chief-states::state` | `chief-states::livewire.model-state` |
| `chief-sites::site-selection.edit-site-selection` | `chief-sites::livewire.edit-model-site-selection` |
| `chief-sites::site-selection.site-selection` | `chief-sites::livewire.model-site-selection` |
| `chief-sites::global-site-toggle` | `chief-sites::livewire.global-site-toggle` |
| `chief-sites::site-toggle` | `chief-sites::livewire.model-site-toggle` |
| `chief-sites::_partials.adding-sites` | `chief-sites::livewire._partials.adding-sites` |
| `chief-sites::_partials.link-status` | `chief-sites::livewire._partials.link-status` |
| `chief-table::livewire.table` | `chief-table::livewire.data-table` |
| `chief-table::reorder.list-item` | `chief-table::livewire.reorder.list-item` |
| `chief-table::reorder.list` | `chief-table::livewire.reorder.list` |
| `chief-urls::links.edit-links` | `chief-urls::livewire.edit-model-links` |
| `chief-urls::links.links` | `chief-urls::livewire.model-links` |
| `chief-urls::links._partials.items` | `chief-urls::livewire._partials.items` |
| `chief-urls::links._partials.redirects` | `chief-urls::livewire._partials.redirects` |
| `chief-hotspots::hotspot-component` | `chief-hotspots::livewire.hot-spot-editor` |
| `chief-image-crop::crop-component` | `chief-image-crop::livewire.image-cropper` |

HotSpots and ImageCrop filesystem views move under `UI/views/`; `file-edit-action.blade.php` and ImageCrop `footer.blade.php` retain their view names. Removed `chief-sites::menu-sites.*` views have no 0.11 replacement; remove project usage only after confirming the obsolete feature is not custom-implemented.

## Events, Methods, And Livewire Semantics

These require contextual edits. Never broad-replace the short event or method names.

| Chief 0.10 | Chief 0.11 | Scope |
| --- | --- | --- |
| `open-edit-state-{componentId}` | `open-edit-model-state-{componentId}` | State component listeners and dispatchers |
| `open-edit-site-selection` | `open-edit-model-site-selection` | Site selection listeners and dispatchers |
| `open-edit-links` | `open-edit-model-links` | URL listeners and dispatchers |
| `EditState::transition($transitionKey)` | `EditModelState::applyTransition($transitionKey)` | Calls, subclasses, and tests |
| `wire:model.change` | `wire:model.live.change` | Only controls that must update immediately on change |
| `wire:model.change.number` | `wire:model.live.change.number` | Immediate numeric select updates |
| `Livewire.hook('request', ({ fail }) => fail(...))` | `Livewire.interceptRequest(({ onError }) => onError(...))` | Custom Livewire request/session handlers |

Component-target changes must use the alias table, including `->to(...)`, `emitDownTo(...)`, `emitToSibling(...)`, Blade tags, tests, and plugin section registration. Livewire 4 event payloads and named/spread arguments must be reviewed against listener signatures; do not globally rewrite `dispatch()` or `emit*()` calls. Custom directives should register safely whether Livewire is already present or later emits `livewire:init`, and should use the directive cleanup callback for third-party instances such as Sortable.
