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
-   [ ] Sorting handle fragments
-   [ ] What happens if you want 2 different types of fragments at the start and end of fragment with 'static' fields in between
-   [ ] Component overview

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


# 0.6.0 Changelog
### Major impact
- Fields are no longer defined on a manager, but instead are set on the managed model. The model is responsible for saving the fields as well. For this reason, there is a convenience trait `ManagedModels\SavingFields` available. This trait
  takes care of most of the usecases for saving fields.
- FlatReference and all associated classes are renamed to ModelReference. All custom implementations of `ActsAsChild` or the former `ProvidesFlatReference`, which is now `ProvidesModelReference` should be updated accordingly.

### Worth your attention but likely a minor impact
- Changed: Module presets Pagetitle and text have moved to the Modules\Presets`  namespace. The two modules are shipped with Chief and are used in the pagebuilder.
- Removed: StoreManager, UpdateManager, `Manager\SavingFields`, `FieldManager` interface and the following manager methods: createFields, saveCreateFields, editFields and saveEditFields.
- Removed: Page methods: `findPublished`, `mediaUrls`, `mediaUrl`
- Removed: Module methods: `mediaFields`

### Minor impact
- Removed: ArrayIterable interface on Filters. Filters class no longer functions as an array like object. This functionality is never used in the chief internals.
- Removed: Custom DynamicAttributes code. We now make use of the `Thinktomorrow\DynamicAttributes` package.


## TODO:
- change statefulcontract to HasPageState.
- ✅ remove dynamic fragment concept altogether. Fragments will always be considered static.
    - ✅ simplify fragmentassistant
    - ✅ rename staticFragment -> fragment
    - ✅ remove FragmentManager preset (staticFragmentmanager renamed to FragmentManager)
- ✅ refactor Viewable: zodat fragments als folder wordt gebruikt voor fragments.
- ✅ migrations cleanup van chief -> terug naar 1 migratie file (voor upgrade voorzien we aparte migration command)
- ✅ nog niet duidelijk dat pagina in draft staat bij begin. Redelijk braafjes.
- ✅ state uit 'permalink' halen.
- ✅ sortable handle zodat tekst kan worden geselecteerd
- field toggle
- image queue
- SEO tracking en events mogelijk maken in chief
- SEO title en description metadata rendering

- shared favorite UI
- errors in sidebar form blijven + tonen
- Relation mapping in ModelReference + save in db as mapped value ipv volledige class
- psalm fixes
- code coverage!
- check performance leaks (blackfire)
- aanmaak fragment waarbij je direct verwacht ook een eerste nested fragment toe te voegen moet niet direct sluiten maar in edit blijven staan zodat je direct een nested fragment kan adden.
- BUG: Creëer fragment in nested fragment --> ga terug via sidebar breadcrumbs zonder op te slaan --> creëer een ander fragment in dan het vorige en sla op --> een lege instance van het eerste nested fragment is opgeslagen.

## BUGS
- ✅ article verwijderd maar context blijft nog. Als ik database reset (en dus ook primary keys) dan wordt bij de nieuwe article ongewenst de oude context aan dit artikel gelinkt.
- ✅ assets blijven 'actief' omdat contexts niet verwijderd worden. Kn dus niet uit mediagallery worden verwijderd. Cleanup?, deleted_at timestamp bij context?
- ✅ verwijder knop in model > status werkt niet
- ✅ choose fragment type, back to select menu, go to other fragment BUT FIRST ONE GETS ADDED!!!!!!!
- ✅ duplicateContext -> nu refereert het nog naar de originele fragmentable indien het geen static fragment is. Is het eerder de bedoeling om ook deze achterliggende class te dupliceren?
- ✅ kopie van quote is beschikbaar maar reeds gebruikt?...
- ✅ proper duplicate context feature (with buttons and all)
- ✅ squanto page layouts are messed up.
- ✅ squanto: also percentage seems off (when one item of the translations is saved, he thinks the we are at 100%).
- ✅ FIX: double value for asset ids... in pivot table NU TEMP de delete assets in DELETEMODEL uitgezet.
- ✅ menu only connects with 'Pages', need to add model_type as well to allow other models
- ✅ menu requires menuLabel for an owner... (internal url) try to remove this and set a title on the menu item itself
- login prompt wordt getoond in sidebar als men niet langer is ingelogd. werkt wel maar beetje raar niet?
- locales voor pagina (view: nl - fr ... version of the page in chief) !!
- replace modelReferenceLabel and modelReferenceGroup with simple adminLabels? e.g. select.group and select.label. Probably need to make it easy to extend and overwrite the adminlabel defaults
- validation op settings page toont: 'validation.required'.
- statusAsLabel() and statusAsPlainLabel()
- na archiveren en terugzetten zijt ge alle links kwijt... Ik wil die terug hebben he!
- UrlHelper has a lot of non-working methods now... Are all of them used?
- menu sorting

HOMEPAGE en FIXED PAGES
- homepage: indien Homepage per taal geen url laten editeren maar labeltje IS HOMEPAGE TONEN (link naar settings om aan te passen). Wel mogelijk maken om redirects toe te voegen.
- hoempage: ook niet toeloaten om / in te geven als url. Dit is nl. enkel voor de homepage.
- extra: hierbij voor alle fixed pages een oplossing voorzien.

## Fragments
- fragment kunnen sharen
- sortering bij nieuwe toegevoegde
- online / offline toggle voor fragments

## DECISIONS
- wat met spirit in chief?

## IMPROVE
- seo defaults weergeven (indien seo niet ingegeven, dan wordt page title gebruikt enz...)
- chief:install met set van default static fragments (textFragment, bannerFragment, galleryFragment) (+ views)
- transport storeRequest and updateRequest: default urls, default short description, default title for page, ...
- perf: not loading the managers + these routes on frontend? / when not logged in as admin?
- remove menu translations table (=> try to remove astrotomic altogether)
- bundle old migrations
- allow to revoke invitation.
- list of reserved model keys such as 'fragments' (used for fragmentModel)
- global search:
  - ik had ergens een usp banner toegevoegd en zou die graag op een andere pagina ook willen toevoegen
  - komt er op de site ergens het woord 'kakkerlak' voor?
- audit records after creation and update
- WithSnippets behavior
- refactor MenuItem so it accepts interface 'MenuItemable' or so
- refactor url stuff (esp. UrlHelper) so it always only uses a 'ProvidesUrl' and add to this interface a 'visitableUrl($locale)' or something to indicate this url is visitable on the site or not.
- memory usage for memoizedUrlRecords is high (lots of model instances)
- refactor view components so adminLabel is not needed as much (or as little as possible)
- remove: translatable logic + migrations + TranslatableCommand in concerns.
- add: thinktomorrow.chief.locales to replace dependency on translatable.locales -> also than we can autofill for 'locales' method on a field
- filter views on index: possibly as components? <x-filters>

## RELEASE
- add: menu migration for translations to dynamic values (script instead of standard migration) + and remove translations table

### types of fragments
// Possible fragmentables are:
// module: fixed promobar
// module: cta block
// couple of pages
// card modules
// automatic collection set (with params like amount, max, sorting, ...)
// snippet (replaces snippet stuff)
// fixed fragment: hero or footer
// ineditable block: promobar


------------------------------------------------------------------------------

## UPCOMING 0.6 (REFACTOR RELEASE)
high impact on manager setup and admin page customisation.
A manager takes care of the routing and responses. It basically acts as a controller. The model itself is responsible for the field definitions and saving of those fields.
- fields are now defined on the managed model, instead of the manager.
- saving of the field values is now the model's responsibility, no longer the manager.

### Manager and model related
- Removed: following methods from a Manager: findManaged,
- Filters have gotten a complete overhaul. Each filter should now implement a `Filter` interface. Some preset filters are available out of the box.
- `UrlField` has been removed. Page urls are no longer maintained as a field. Field behavior for the url management was always a bit too forced. Instead all pages will automatically contain the url management in the sidebar of their admin page.
  The links mgmt segment is auto-injected when that page model implements the `ProvidesUrl` interface.
- Removed: Translatable logic based on the `astrotomic/laravel-translatable` package, which required a separate translation table.
  This will no longer be required in a blank Chief project. For translatable content, the default models now rely on the `Thinktomorrow/DynamicAttribures` package instead.
### View related
- Removed: custom `project-head` and `project-footer` blade files. These served as a placeholder to add custom code to your projects' admin pages. It is however rarely used and now obsolete due to the new way how admin pages are constructed.
- Removed: unused `ActsAsMenuItem` interface.
