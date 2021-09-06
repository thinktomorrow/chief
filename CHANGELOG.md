# Changelog

All Notable changes to the `chief` application template will be documented in this file. Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/)
principles.

## next release

-   Added: Visitable::response() which renders the page view by default but also allows for custom handling a frontend request of the model.
-   Added: FieldWindow and FieldSet models that allow for more control on your field structure.
-   Added: File thumbnail is now shown in the field window for pdf files.
-   Fixed: Link window could not be used without a State window.
-   Removed: Field::component() to select the window where this field should be placed in. You should now use the FieldWindow syntax instead.
-   Changed: AbstractField default label value was changed from using the field key to null. If a label is not specifically given, the field will display without it.

## 0.6.0

Release of the 0.6 branch. An exhaustive list of changes from the 0.5 release isn't available. Please check the upgrade guide in the docs instead.
