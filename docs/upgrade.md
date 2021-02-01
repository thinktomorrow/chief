# upgrade guide from 0.5 to 0.6

In general we don't provide any precaution deprecations and force the upgrade to convey to the changed api. 
This is meanly done because chief is still in development and currently mainly used by ourselves. That being
said, we've tried to thoroughly describe the required changes in this upgrade guide. 

### ChiefProjectServiceProvider::registerManager
ChiefProjectServiceProvider::registerManager now only expects one parameter, namely the manager classname.
 The model class and optional tag parameters are no longer required. Also the `registerPage` and `registerModule` methods are no longer 

### Locales
The locales for chief should now be set via the chief config file. Note that you can still set this per model or per field. 
TODO: how then he Ben, how!?

### Tag fields for create form
 You need to tag the fields which you want to show up in the create form. 
 Sometimes you don't want to create a new model with all its given fields.
 The edit form will always contain all the fields, including those tagged with a _create_ tag.

### Chief navigation
Navigation items are set by default as separate menu entries. With the tags given to each registered manager, you can alter the navigation.
...

### ModelReference
FlatReference and all associated classes are renamed to ModelReference. 

### Required database changes
- The Chief User class is moved to another namespace. Change the references to the class in all database records.
Change `Thinktomorrow\Chief\Users\User` to `Thinktomorrow\Chief\Admin\Users\User` in the model_has_roles and activity_log tables.

FRONTEND
- visitable: 
    - url(), with url and 
    - renderContent(), usually has a pagebuilder content and 
    - can be published
    - viewable: can also be present as reference in another pagebuilder content

CONTENT:
- Sectionable: viewable: 
    - no url
    - is only rendered as part of pagebuilder
    - cannot be published
- Content scheme:
    - visitable page has allowed list of sections.
    - each sections can consist of 
