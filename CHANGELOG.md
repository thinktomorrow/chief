# Changelog
All Notable changes to the `chief` application template will be documented in this file. Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) 
principles.

## unreleased
- Fixed: issue where chief error page is shown when path contains the 'admin' string.
- Fixed: added chief guard when fetching user out of request to prevent conflict with front end logins.

## 0.5.2 - 2020-18-05
- Added: option to add charactercount to textfield and inputfield. Added this to default seo description and seo title fields.
- Added: search in mediagallery popup
- Added: if a mediafield allows multiple images, the mediagallery popup now allows multiple selection

## 0.5.1 - 2020-11-05
- Fixed: teaser helper function changed to work with multibyte character strings

## 0.5.0 - 2020-05-04
- Added: A new page can be created based on an existing template. This will duplicate the pagebuilder content and structure.
- Added: Pagefield used to select an internal page
- Added: chief admin toast - a widget element on frontend for quick page edit and preview mode toggle
- Added: dynamic attributes. [https://thinktomorrow.github.io/package-docs/src/chief/managers.html#dynamic-attributes](view docs)
- Added: `Manager::editView()` and `Manager::createView` provide the option to customise the view filepath for the create and edit admin views.
- Added: `Manager::editFields()` and `Manager::createFields` provide the fields for respectively the create and edit form views.
- Added: A `Field` now has the ability to be tagged. This allows to easily group fields on the admin create or edit views. One or more tags can be added with the `Field::tag()` method.
- Added: `Field::render(string $locale = null)` to render a field onto the page. A field is now responsible to render its own content.
- Added: `Field::model($model)` to set the data source for this field. By default an Eloquent model is expected. The model is added automatically to each field behind the scenes.
- Changed: `Field::getValue(string $locale = null)` no longer requires to be passed the model. It now only accepts one optional locale argument.
- Changed: renamed `Thinktomorrow\Chief\Management\Details\Sections` to `Thinktomorrow\Chief\Management\Details\DetailSections`.
- Changed: renamed `Thinktomorrow\Chief\Management\Details\HasSections` to `Thinktomorrow\Chief\Management\Details\HasDetailSections`.
- Changed: Blade component alias `chiefformgroup` renamed to `formgroup`
- Changed: Trying to retrieve a Fields offset e.g. `$fields['title']`), which doesn't exist, now results in an exception being thrown.
- Changed: `Field::default()` sets the default value in case the model does not provide one. `Field::value()` forces the value.
- Changed: baseUrlSegment method is no longer a static method and requires to be an instance method.
- Changed: Moved the Healthmonitor classes to the `src/System` directory. You'll need to update your chief.php monitor entries.
- Fixed: Creating own modules for pages with custom tables now works as expected.
- Fixed: small bug in multiselect that would set the value as 'length' if the options array was empty.
- Removed: `ManagedModel::previewUrl()`. Use the `ManagedModel::url()` instead.
- Removed: the `$manager` instance is no longer available inside a field view. You can always pass this to the view via the `Field::viewData()` method.
- Removed: `Manager::fieldValue()` and `Manager::renderField()` methods. A field is now responsible for rendering its content, not the manager.
- Removed: `RenderingFields` trait as the methods `Manager::fieldValue()` and `Manager::renderField()` are no longer being used.
- Removed: `Thinktomorrow\Chief\Fields\FieldArrangement`, `Thinktomorrow\Chief\Fields\FieldTabs` and `Thinktomorrow\Chief\Fields\RemainingFieldsTab` classes. Admin views can now be edited as blade files.

## 0.4.12 - 2020-03-18
- Changed: passed the media api url from outside the js and vue scripts so the urls are in line with the rest of the admin
- Fixed: removing a quill editor text field in pagebuilder is now possible

## 0.4.11 - 2020-03-18
- Added: generate sitemap command and schedule
- Fixed: quill editor did not save the content in the pagebuilder text module.

## 0.4.10 - 2020-03-16
- Fixed: changing urls can now be saved again.

## 0.4.9 - 2020-03-11
- Fixed: full height bugs safari

## 0.4.8 - 2020-03-10
- Fixed: redactor editor for pagebuilder text fields

## 0.4.7 - 2020-03-09
**This release requires a migration to implement the new page state logic.**
This release introduces a couple of important changes: 
- There is now a new state logic for pages: archived, draft, published, deleted. These states are kept in one database column instead of being scattered around.
- There is now proper image validation for the image and file fields. 
- There is now async image upload available on the slim component.

More info on upgrading can be found in the [https://thinktomorrow.github.io/package-docs/src/chief/upgrading.html#upgrading-from-0-4-6-to-0-4-7](chief documentation).


- Added: A generic `AssistantController` as a default dispatcher for assistant actions. Default assistants `ArchiveAssistant` and `PublishAssistant` now utilise the default `chief.back.assistants.update` and `chief.back.assistants.view` which points to this controller.
- Added: config option `admin-filepath` that can be used to add custom chief routes. The routes defined in this file will only be accessible on authenticated sessions.
- Added: `PageState` object which represents the page state: draft, published, archived or deleted. This is visible in the database as a current_state column.
- Added: image validation rules: `required`, `dimensions`, `min`, `max`, `mimetypes`. Visit the [https://laravel.com/docs/6.x/validation#available-validation-rules](laravel documentation) on how to work with these.
- Changed: Images in slim component are now being uploaded asynchronous to avoid server errors on request and file size.
- Changed: the underlying StateMachine now allows to manage multiple states for one model.
- Changed: Routes are now loaded by a separate `ChiefRoutesServiceProvider` which refers to two route files: `chief-open-routes` for non authenticated endpoints and `chief-admin-routes` for authenicated endpoints.
- Changed: Redactor uploads are now via base64 data urls. A new js function `chiefRedactorImageUpload` should be used for the redactor imageUpload script.
- Assistants now need to implement the `Thinktomorrow\Chief\Management\Assistants\Assistant` contract.
- Removed: csrf token verification
- Removed: `Manager::model()` has been removed. You should either use `Manager::modelInstance()` for an empty generic model instance or `Manager::existingModel()` to retrieve the model record
- Removed: `Publishable::sortedByPublished` method since it has no effect in sorting by published date.
- Removed: tinker package.
- Removed: adding collections to a menu item is no longer supported. Instead add each page one by one.
- Fixed: chief error now returns expected 500 status instead of 200.
- Fixed: phpstan released lots of static analysis bugs which have been solved.
- Fixed: uploads via redactor that are too large now stop the script and notify the user.

### Field changes
- Added: introduced a `Field` interface for stricter Field usage throughout the application. 
- Added: `Field::getPlaceholder()` is added to retrieve a placeholder value.
- Removed: `Field::getFieldValue()` is removed. Use the new `Field::getValue(Model $model = null, ?string $locale = null)` method instead.
- Removed: `Field::key()` is now only used to set a custom key.  To retrieve the key use the `Field::getKey()` method.
- Removed: `Field::name()` is now only used to set a custom name.  To retrieve the name use the `Field::getName()` method.
- Removed: `Field::column()` is now only used to set a custom column.  To retrieve the column use the `Field::getColumn()` method.
- Removed: `Field::default()` method is removed. From now on, use `Field::value(string $value)` to set the default value.
- Removed: `DocumentField` is removed. Replace its usage with `FileField` instead, which has the same behavior. It better reflects its nature because also images are allowed here.
- Changed: `MediaField` is now an abstract class. Replace its usage with `ImageField` instead. This naming better reflects the image only aspect of this formfield.
- Changed: `Field::multiple()` is only used to set the multiple state. To retrieve this value, it is replaced by `Field::allowMultiple()`.

## 0.4.6 - 2020-02-20
- Fixed: show context menu on edit page if even without update permission
- Fixed: disabled vue from compiling mustache brackets in textareas
- Added: added audit logs for menu items
- Removed: adding collections to a menu item is no longer supported. Instead add each page one by one.

## 0.4.5 - 2020-01-14
- Fixed: z-index on redactor toolbar lowered so it doesnt overlap modals/dropdowns
- Fixed: image filter for mediagallery api call now correctly offsets without counting non-images.

## 0.4.4 - 2020-01-13
- Fixed: modal.vue close button doesnt submit forms anymore

## 0.4.3 - 2020-01-10
- Added: Functionality to upload existing assets.
- Changed: changed getmedia assets fetch to use direct asset relation so it doesnt use fallback 
- Fixed: issue where module add button didn't show for developer account
- Fixed: slow loading of admin index pages due to overuse of `Page::url()` method
- Fixed: document upload for multiple locales

## 0.4.2 - 2019-11-27
- Fixed: modulemanager route function to work with laravel 6.6 
- Fixed: issue where updating url could result in a duplicate db entry
- Fixed: Redactor rich links suggestions now show urls for the current selected locale
- Removed: htmlpurifier which caused inconsistent saving of text module
- Removed: dropped support for laravel 5.7

## 0.4.1 - 2019-11-26
- Added: extra parent and request parameters for query set methods.
- Fixed: preserve old input after failed validation for input field
- Fixed: dont show modules tab on page edit page when there aren't any modules that can be created 
- Fixed: issue where `Set::paginate()` would perform a second db query to fetch all results, even when total count was already known.

## 0.4.0 - 2019-11-19
- Added: function valueResolver on field to customize how a value is retrieved from the database
- Added: `Field::default()` method to set default field value.
- Added: nomadic trait. A nomadic page or module can only be edited by admin and only one can exists.
- Fixed: issue where app name would not show up in chief emails. replaced `client_app_name` by `app_name`.
- Fixed: Multiselect placeholders were looking buggy. Now they don't.
- Fixed: issue where deleting a model would not delete any existing relations.
- Changed: updated Assetlibrary to 0.6

## 0.3.4 - 2019-10-18
- Added: config option `thinktomorrow.chief.route.prefix` to change default `/admin` url prefix if needed.
- Added: selected module in pagebuilder now displays an edit link
- Added: Checkbox field
- Added: Laravel 6 support
- Changed: Healthmonitor checks are now defined in the chief.php config file.
- Changed: FieldType now accepts custom types. It no longer requires a type to be one of the provided defaults.
- Fixed: wysiwyg editor was missing on the create page. Added extra flag to disable image upload.
- Fixed: Slug of deleted module is now allowed to be reused. This used to give an unique validation constraint.
- Fixed: isActiveUrl helper method can now check for full url
- Fixed: show edit link in context menu in modules tab on page editpage
- Fixed: deleting a page now also deletes related url records.
- Fixed: issue where unique url validation didn't take the base url segment into account.
- Fixed: issue where long pagetitle would overflow the admin header

## 0.3.3 - 2019-09-30
- Fixed: fixed bug with set viewkey
- Fixed: required on settings
- Fixed: layout issue with dropdown which would push wrapping element down
- Fixed: image uploads
- Fixed: image sorting
- Changed: optimize url record retrieval for large datasets with a simple memoization.
- Changed: small changes to pagebuilder and removed redactor
- Changed: BREAKING - FindAllManaged function on manager has been removed in favor of indexCollection
- Changed: increased max timeout and memorylimit for image upload/conversions


## 0.3.0 - 2019-8-20
- Fixed: translatable media is now saved properly
- Fixed: selectfield return empty array instead of array with null value
- Removed: Homepage setting and `chief-settings.homepage` config value
- Removed: deprecated `Page::menuUrl()` in favor of `Page::url()`.
- Removed: `Page::hasPagebuilder()` and `Page::pagebuilder` property.
- Removed: `Page::findBySlug()` and `Page::findPublishedBySlug()`.
- Removed: Homepage setting and `chief-settings.homepage` config value
- Removed: `ActsAsChild::viewKey()` contract method requirement. This is now the responsibility of the `ViewableContract`.
- Removed: `ActsAsParent::viewKey()` contract method requirement. This is now the responsibility of the `ViewableContract`.
- Removed: Honeypot middleware and helper.
- Removed: migration columns start_at, end_at and featured from table pages.
- Removed: $dates on page and module since these fields are all set through traits
- Removed: relation.blacklist in config/chief.php
- Changed: By default the chief route `pages.show` is autoloaded by the package. This can be opted out by setting the `chief.route.autoload` value to false.
- Changed: The locale placeholder has changed from '*' to ':locale'. This is used in a field name value to dynamically fill in each locale. e.g. descriptions[:locale] will be composed to descriptions[nl], descriptions[en], ...
- Changed: `ProvidesUrl` contract to identify models that should be retrievable by direct url.
- Changed: By default the chief route `pages.show` is autoloaded by the package. This can be opted out by setting the `chief.routes.autoload` value to false.
- Changed: don't show tabs when there is just one language in menubuilder. Case: "Of kies een eigen link."
- Changed: construct on archivable trait to inizialize to prevent issues with $dates field setting
- Changed: grouping of child pages as a collection is no longer based on the view key as a grouping id. Rather the `flatReferenceGroup()` value is used instead.
- Changed: Fields::add() is made immutable so it no longer changes current collection but returns a new Fields instance.
- Changed: replace htmlawed with HtmlPurifier
- Added: `ViewableContract` to identify models that should be rendered on the site.
- Added: Manager assistant `UrlAssistant` which takes care of the page urls.
- Added: changing page url keep old url as 301 redirect to the new one
- Added: when archiving a page another page can now be set as redirect
- Added: improved field validation design
- Added: `Manager::fieldsWithAssistantFields()` which also include any assistant fields. This is mostly used internally.
- Added: added seo_keywords validation on length
- Added: relation.children in config/chief.php
- Added: config setting `strict` to display any non-fatal errors which are otherwise silently ignored. Defaults to correspond with the project's APP_DEBUG value.
- Added: Assistants can now add their own field to the manager edit form via a `Assistant::fields(): Fields` method.
- Added: Assistants can handle the saving of this field via a custom method following the same naming convention for custom save methods on the manager. e.g. saveExampleField
- Added: Fields::merge() method which can be passed another Fields object. A Field value with the same key will overwrite the existing one.
- Added: Html sanitization on updatesections
- Added: healthmonitor homepage set check
- Added: editor option in chief config to select html editor of choice. Default to quill.
- Deprecated: `Page::hasPagebuilder()` since no longer used. Scheduled to be removed in version 0.4.

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
