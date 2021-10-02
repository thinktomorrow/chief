# Changelog
All Notable changes to the `chief` application template will be documented in this file. Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/)
principles.

## next release
- Add: option to set custom from email address. Via chief setting from_email and from_name values
- Added: sidebar component::onComponentReloading and component::onComponentReloaded listeners.
- Added: option to disable livewire reload after sidebar component submission by passing livewire=false in response data.
- Added: response data to sidebar event payload data
- Added: option to set breadcrumb on edit page
- Added: seperate handleStore and handleUpdate methods
- Fixed: repeatfield type would overwrite existing field indices so only last one would get stored. Therefore disabled vue fields for repeatfield.
- Removed: sidebar component::onComponentReload event listener. use the onComponentReloaded instead

## 0.6.4 - 2021-09-24
- Fixed: fieldSets loops are more consistent across the admin forms
- Fixed: fragment deletion returned non async response
- Changed: window padding should be given explicitly on a window: window-xs, window-md

## 0.6.3 - 2021-09-23
- Added: RepeatField as Addon which allows to duplicate input fields.
- Added: vueFields js function to init vue fields based on the \[data-vue-fields].
- Added: width option for Fields to set custom width: 1/2, 1/3, 2/3.
- Added: Model::adminConfig()->setIndexCardView() method to set a custom card view for the admin index.
- Changed: AbstractField default label value was changed from using the field key to null. If a label is not specifically given, the field will display without it.
- Fixed: After focussing a sidebar input, there was no scroll back to top.
- Fixed: Chief pagination used the project default pagination which is just weird.
- Changed: Prefixed all chief blade components with chief-. E.g. <x-icon-label> is now <x-chief-icon-label>.

## 0.6.2 - 2021-09-22
- Added: Visitable::response() which renders the page view by default but also allows for custom handling a frontend request of the model.
- Added: FieldWindow and FieldSet models that allow for more control on your field structure.
- Added: File thumbnail is now shown in the field window for pdf files.
- Added: Hierarchy component to visually show a model hierarchy by adding an indent
- Fixed: Link window could not be used without a State window.
- Changed: Updated thinktomorrow/vine dependency. This requires some changes in the projects menu views.
- Removed: Field::component() to select the window where this field should be placed in. You should now use the FieldWindow syntax instead.

## 0.6.0
Release of the 0.6 branch. An exhaustive list of changes from the 0.5 release isn't available. Please check the upgrade guide in the docs instead.
