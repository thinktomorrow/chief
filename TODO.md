# TODO

## Frontend

-   [x] Multiselect fields styling
-   [x] Modal styling (basic styling done) --> archive-modal, delete-modal ...
-   [x] Notifications styling (mostly error, warning... colors)
-   [x] Manager transition different display options
-   [x] Squanto view files
-   [x] Singles create view
-   [x] Chief custom error page (onze devs zijn er mee bezig ...)
-   [x] Registering + password reset flow view files
-   [x] refactor formgroup with working labels, inline errors ...
-   [ ] Rework all error, info, warning and success styling
-   [ ] Redactor update + plugin check
-   [ ] Redactor init on custom event
-   [ ] Closing modal via backdrop doesn't close sidebar with editing of the pagetitle
-   [ ] Multiselect bij mediagallery is nog fucked up
-   [ ] pagination view update
-   [ ] Seems like fragments sidebar errors don't work yet
-   [ ] Thumb css rework
-   [ ] Status component setup check by Ben

### Fragments

-   [x] After addings a fragment, you can't add another one (BUG)
-   [x] After addings a fragment, you can't sort anymore (BUG)
-   [x] After deleting a fragment, you can't sort anymore (BUG)
-   [x] fragment selection element shouldn't hide on click outside of element
-   [x] fragment selection element close button
-   [x] trigger elements reload after fragment selection element is created
-   [x] fragment selection flow
-   [x] fragment selection shouldn't have a close trigger if there are no fragments yet
-   [ ] nested fragments testing
-   [ ] after adding or deleting 2 elements, all fragments are acting as selection trigger elements
-   [ ] wireframe-like icons for each fragment type
-   [ ] make it obvious that fragment elements are draggable/sortable

### Nice to have

-   [ ] Digital huisstijl guide
-   [ ] window component? (title, spacing ...)
-   [ ] thinktomorrow/chief-redactor package
-   [ ] How to use project specific tailwindcss classes (admin fragments, dashboard widgets ...) which are not included in default Chief build?
-   [ ] Chief admin translations
-   [ ] Lifecycle hooks documentation (+ rules for custom events)
-   [ ] Happy first path, tutorial, notifications, tips
-   [ ] Teaser
-   [ ] Update getchief

### Field Component

-   [ ] Status component
-   [ ] Images getValue() always returns an empty array
-   [ ] Working truncate for both normal strings and HTML strings
-   [ ] Fix charactercount for normal input fields (script doesn't init)
-   [ ] Add inline errors for every field type
-   [ ] Sidebar form validation (POST request doesn't seem to work if validation fails)
-   [ ] Range fieldcomponent/field view upgrade
-   [ ] Date fieldcomponent view upgrade
-   [ ] Possibility to set custom template for field component view per field
-   [ ] Checkbox, select, radio and page fields view check (by Ben)

## SEDS implementation of Chief

-   [ ] The command php artisan chief:page is working great. Now we need nice a finished page default config.
-   [ ] The first page you make, which is almost always going to be the homepage, needs to be published AND given an url '/'.
        Otherwise the page won't be visible or selectable as homepage in settings.
-   [ ] Menu items can't be created. Error = Undefined index: path
