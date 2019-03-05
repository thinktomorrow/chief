# Changelog
All Notable changes to the `chief` application template will be documented in this file. Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## unreleased
- Add: add `addTab()` method to `Thinktomorrow\Chief\Fields\FieldArrangement` class.
- Add: possibility to display sidebar data on manager index page via a `sections()` method on the manager.

## 0.2.4 - 2019-03-01
- Update packages including thinktomorrow/squanto to 0.5.5
- Change: with the update of squanto empty translation lines will now be kept as valid values, and no longer cascades down to the translation file source.

## 0.2.3 - 2019-02-28
- Add: view folder for sets was only targeted at views/front/modules/ and is now also allowed under view/modules.
- Fix: Don't show menutitle when title is not set in given locale.
- Fix: missing argument for permission exception in PageManager.
- Change: Hide navigation item if user has no permission to view the items page.

## 0.2.2 - 2019-02-21
- Fix: force package version of spatie/image-optimizer to 1.1.4 since 1.1.5 requires php 7.2.
- Change: modules view folder was only targeted at views/front/modules/ and is now also allowed under view/modules.

## 0.2.1 - 2019-02-14
- Fix: pagebuilder removal bug where removing a multilingual text section would only remove the current visible translation.
- Fix: pagebuilder sorting bug that occurred in a specific case where text placed on top could not be dragged.
- Fix: menu where now a custom url can be set as a relative path, e.g. /contact.
- Fix: fieldTab value is now passed to a custom field tab view for custom rendering.
- Change: updated vine package to latest version 0.2.4.   
- Add: option to choose which managers to display on dashboard by registering them with a 'dashboard' tag.

## 0.2.0 - 2019-02-04
- First tagged release of the package.
- Anything before 0.2 is considered early development phase and is not documented in the changelog.
