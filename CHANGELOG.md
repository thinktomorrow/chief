# Changelog
All Notable changes to the `chief` application template will be documented in this file. Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## unreleased
- Fixed: header margin bug by updating warpaint 0.0.10
- Fixed: fallback set to default page view
- Fixed: respect redirectTo url for AuthenticationException in L5.7

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
