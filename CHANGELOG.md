# Changelog
All Notable changes to the `chief` application template will be documented in this file. Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## unreleased
- Added: added seo_keywords validation on length

## 0.2.14 - 2019-6-18
- Added: add seo_image to page seo tab
- Fixed: remove own module on page now works.
- Fixed: apply permissions on archive/publish buttons
- Fixed: removing document and uploading works at the same time.
- Fixed: media/document fields can be translatable
- Fixed: text field wysiwyg dutch translations added
- Fixed: fixed error translations for module creation and menuitem creation
- Fixed: CRUD for menu's now enforce the page permissions

## 0.2.13 - 2019-05-23
- Fixed: Find published page where the slug matches one of the application base url segments. 

## 0.2.12 - 2019-05-22
- Added: Pagebuilder action labels to better describe their intent
- Changed: save button on the edit pages only displays if the model can be updated
- Changed: Name of a collection page title on creation and h1, h2 and h3 in redactor
- Changed: implement thinktomorrow/url instead of copypasting the classes.
- Changed: use themed password reset mail layout instead of default laravel one.
- Changed: use chief translations for admin password reset form validation. This is provided via `chief::` lang namespace.
- Fixed: pagebuilder no longer shows modules that are specific for other pages.
- Fixed: regular checkboxes are no longer hidden by default.

## 0.2.11 - 2019-04-23
- Added: empty state for menu items
- Added: allow Field key value to be set via `Field::key()` method
- Changed: filters dont show if you have no models except if you're filtering and have an empty result
- Changed: on unarchive, if the manager has the publish assistant, set to draft instead of online.
- Changed: changed the permission for details of squanto lines to delete-squanto permission so only developers can see this.
- Removed: ManagerThatPreviews, ManagesPreview, ManagesPublishingStatus and implemented the functions in publishassistant.
- Removed: permission permissions, since permissions require code changes you can't manage permissions from the admin panel
- Fixed: document media can be removed now
- Fixed: document upload will adhere to the multiple flag now.
- Fixed: user can't block themself anymore
- Fixed: SelectField::multiple defaults to true when no boolean argument is passed.

## 0.2.10 - 2019-04-10
- Fixed: avoid morphableInstantiation looping over models which are not morphable.

## 0.2.9 - 2019-04-10
- Changed: moved `Assistant` contract to `/Managers/Assistants/` folder.
- Changed: Publication logic is no longer baked in Pagemanager but added as a Manager assistant.
- Fixed: Fixed saving new page without title. `required-fallback-locale` is now an implicit rule.
- Fixed: Prepend and Append methods on fields don't require the field to be translated anymore
- Fixed: integration tests by checking view path of admin routes.

## 0.2.8 - 2019-04-01
- Added: A Page can now set a fixed base url segment (e.g. /news). This is set on the Page model via a `baseUrlSegment` property. This accepts a single string or an array of localized segments where the key should be the locale itself.
- Added: pages.home route is now configurable in the chief config file.
- Changed: minimum requirement for Chief is now php 7.2.*.
- Changed: from now on Laravel 5.8.* is used as expected laravel version. Updated to phpunit 8 as well.
- Changed: `ActsAsMenuItem::menuUrl()`  and `Page::menuUrl()` are deprecated. Use `url()` method instead.
- Fixed: Adding more than one snippet broke the parsing of these snippets.

## 0.2.7 - 2019-03-21
- Added: Publish filter added to pagemanager.
- Changed: own settings page now requires the user to have the `update-you` permission. In existing projects you can add this permission by running: `php artisan chief:permission update-you`. Next you'll need to add this permission to all the roles in your system. This is done via the UI.
- Removed: We removed the unused `view-setting` permission for the default roles setup. 
- Removed: manager::archive() method in favor of ArchiveAssistant flow.
- Removed: pagemanager::archive() method in favor of ArchiveAssistant flow.
- Removed: unused views: authorization.permissions.*, _elements.mediagroup-*, authorization.roles._deletemodal
- Removed: unused crudcommands: archive,create,update page commands 
- Fixed: only a developer can edit/update an user with role developer.
- Fixed: only a developer can assign an user with the developer role.
- Fixed: improved user management UI 
- Fixed: header margin bug by updating warpaint 0.0.10
- Fixed: fallback set to default page view
- Fixed: pagebuilder module section now save in right order
- Fixed: respect redirectTo url for AuthenticationException in L5.7
- Fixed: allow field description value to contain html
- Fixed: cast expires_at to date on invitation

## 0.2.6 - 2019-03-12
- Added: seo_keywords added to page seo tab. Please run `php artisan migrate` to add the seo_keywords column!
- Added: Managers can have filters to query the admin index.
- Added: Pagebuilder better outlines the dropzone in sort mode.
- Changed: `findAllManaged()` has changed and now contains the filtering logic. Please keep in mind, should you have written your own `findAllManaged` version.
- Removed: PageUpdateRequest, PageCreateRequest, PagesController, ModuleUpdateRequest
- Removed: unused view files for modules/pages
- Removed: database migration comments to avoid buffer warnings in tests
- Fixed: menuitems of offline pages now show in admin.
- Fixed: role name cant contain spaces.

## 0.2.5 - 2019-03-05
- Added: add `addTab()` method to `Thinktomorrow\Chief\Fields\FieldArrangement` class.
- Added: possibility to display sidebar data on manager index page via a `sections()` method on the manager.

## 0.2.4 - 2019-03-01
- Update packages including thinktomorrow/squanto to 0.5.5
- Changed: with the update of squanto empty translation lines will now be kept as valid values, and no longer cascades down to the translation file source.

## 0.2.3 - 2019-02-28
- Added: view folder for sets was only targeted at views/front/modules/ and is now also allowed under view/modules.
- Changed: Hide navigation item if user has no permission to view the items page.
- Fixed: Don't show menutitle when title is not set in given locale.
- Fixed: missing argument for permission exception in PageManager.

## 0.2.2 - 2019-02-21
- Changed: modules view folder was only targeted at views/front/modules/ and is now also allowed under view/modules.
- Fixed: force package version of spatie/image-optimizer to 1.1.4 since 1.1.5 requires php 7.2.

## 0.2.1 - 2019-02-14
- Added: option to choose which managers to display on dashboard by registering them with a 'dashboard' tag.
- Changed: updated vine package to latest version 0.2.4.   
- Fixed: pagebuilder removal bug where removing a multilingual text section would only remove the current visible translation.
- Fixed: pagebuilder sorting bug that occurred in a specific case where text placed on top could not be dragged.
- Fixed: menu where now a custom url can be set as a relative path, e.g. /contact.
- Fixed: fieldTab value is now passed to a custom field tab view for custom rendering.

## 0.2.0 - 2019-02-04
- First tagged release of the package.
- Anything before 0.2 is considered early development phase and is not documented in the changelog.
