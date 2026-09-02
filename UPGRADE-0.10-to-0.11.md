# Chief upgraden van 0.10 naar 0.11

Deze handleiding is bedoeld voor consumer-projecten die Chief 0.10 gebruiken en naar Chief 0.11 en Livewire 4 gaan. De Chief 0.11-broncode in deze repository is het canonieke doel voor deze tekst.

> Analyse uitgevoerd op 2 september 2026. Alle absolute projectpaden en aantallen in de appendices zijn voorbeelden en momentopnames. Herhaal de audit op de branch en commit die werkelijk worden uitgerold. In de geaudite consumer-projecten zijn voor deze documentatie geen tests uitgevoerd.

## Scope en vereisten

| Onderdeel | Doelversie |
|---|---:|
| PHP | `^8.4` |
| Laravel | `^12.0` |
| Chief | `^0.11` |
| Livewire | `^4.4` |

Gebruik de vrijgegeven constraint `thinktomorrow/chief:^0.11` zodra een 0.11-tag beschikbaar is. Alleen tijdens pre-releasewerk mag een gecontroleerde upgradebranch tijdelijk een expliciete repository/branch-constraint naar de beoogde Chief-commit gebruiken. Leg geen blijvende `@dev`, branch-alias of lokale path-repository vast als productieoplossing.

Chief 0.11 vereist Laravel 12 en trekt Livewire `^4.4` mee. Behandel dit als één upgrade: een gedeeltelijke combinatie van Chief 0.11 met Livewire 3 of Laravel 11 is geen ondersteund eindpunt.

## 1. Preflight

- [ ] Maak een afzonderlijke upgradebranch vanaf de exacte productiecommit.
- [ ] Noteer PHP-, Composer-, Node- en databaseversies van productie en CI.
- [ ] Maak een herstelbare databaseback-up en controleer hoe uploads en gepubliceerde assets worden hersteld.
- [ ] Maak een snapshot of release-artifact van de huidige applicatiecode, `composer.lock` en frontendbuild.
- [ ] Controleer `git status --short`; begin bij voorkeur met een schone worktree.
- [ ] Gooi een vuile worktree nooit weg. Commit eigen werk, stash het bewust of maak een extra worktree.
- [ ] Noteer de exacte Chief-patch uit `composer.lock`, niet alleen de constraint uit `composer.json`.
- [ ] Controleer alle eigen packages en plugins op hun Laravel-, Livewire- en Chief-constraints.
- [ ] Plan een onderhoudsvenster als migraties, assetomschakeling of cache-/proxywijzigingen niet atomair kunnen gebeuren.

```bash
git status --short
git switch -c upgrade/chief-0.11
composer show --locked thinktomorrow/chief
composer show --locked livewire/livewire
composer show --locked laravel/framework
php -v
composer --version
node --version
npm --version
```

### Startpatch vóór 0.10.30

Projecten onder Chief `0.10.30` kunnen nog een package-migratie open hebben die invitations/invitation-gerelateerde databaseonderdelen hernoemt of voorbereidt. Spring niet blind rechtstreeks naar 0.11.

- Bepaal met `composer show --locked thinktomorrow/chief` de werkelijk geïnstalleerde patch.
- Inspecteer `php artisan migrate:status` vóór de dependency-update.
- Bekijk specifiek package-migraties die vanaf 0.10.30 beschikbaar kwamen, inclusief invitation- en user-deletionwijzigingen.
- Upgrade zo nodig eerst gecontroleerd naar de laatste 0.10-patch, voer de goedgekeurde 0.10-migraties uit en maak daarna een nieuw herstelpunt.
- Controleer eigen queries, modellen, mailtemplates, foreign keys en rapportage rond invitations op oude namen.

Een Composer-constraint zoals `^0.10.18` bewijst niet dat `0.10.18` is geïnstalleerd; `composer.lock` is beslissend.

## 2. Audit vóór wijzigen

Zoek in applicatiecode, projectplugins, tests, Blade-overrides en JavaScript. Negeer bij de beoordeling gegenereerde bestanden in `storage/framework/views`, maar wis die later wel via `optimize:clear`.

```bash
rg -n "chief-wire::|chief-form::dialog|chief-fragments::(contexts|context|add-fragment|edit-fragment)" app resources routes tests
rg -n "Thinktomorrow\\\\Chief\\\\.*(Livewire|Component|ShowsAsDialog)" app resources routes tests
rg -n "Livewire::component|Livewire::setUpdateRoute|Livewire\\.hook|@entangle|wire:model|wire:transition|wire:scroll" app resources routes tests
rg -n "dispatch\\(|Livewire\\.on|addEventListener|event\\.detail" app resources routes tests
rg -n "chief-form::templates.form-in-sidebar|vendor/chief|vendor/chief-" app resources routes tests
```

Maak van elke treffer een keuze: vervangen, rebased override, regressietest of aantoonbaar dode code verwijderen. Een zoekresultaat in een test is even belangrijk als productiecode; een niet-ontdekte testsuite is geen bewijs dat de code compatibel is.

## 3. Dependencies veilig bijwerken

### Eerst conflicten begrijpen

```bash
composer why-not php 8.4
composer why-not laravel/framework ^12.0
composer why-not livewire/livewire ^4.4
composer why-not thinktomorrow/chief ^0.11
composer outdated --direct
composer audit
```

Los constraints in rootprojecten en eigen packages expliciet op. Gebruik geen `--ignore-platform-reqs`, `--ignore-platform-req`, `--no-audit` of tijdelijke uitschakeling van Composer security blocking om de update te forceren. Een security advisory is een blocker die moet worden opgelost, niet omzeild.

### Releaseconstraint

Zodra Chief 0.11 is vrijgegeven:

```bash
composer require php:^8.4 laravel/framework:^12.0 livewire/livewire:^4.4 thinktomorrow/chief:^0.11 --with-all-dependencies
composer validate
composer audit
composer show --locked thinktomorrow/chief
composer show --locked livewire/livewire
composer show --locked laravel/framework
```

Laat Composer `composer.json` en `composer.lock` samen wijzigen. Bekijk de volledige lock-diff, inclusief indirecte Laravel-, Symfony-, Squanto-, URL-, assetlibrary- en pluginupdates. Voer de update eerst lokaal/CI uit; laat productie alleen het beoordeelde lockbestand installeren:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
```

## 4. Livewire-componentaliases

Chief 0.11 groepeert componenten per domein met `Livewire::addNamespace()`. Vervang alle oude aliases; er is geen compatibiliteitslaag waarop consumer-projecten mogen vertrouwen.

### 32 core-aliases

| Domein | Chief 0.10 | Chief 0.11 |
|---|---|---|
| Assets | `chief-wire::file-gallery` | `chief-wire-assets::asset-gallery` |
| Assets | `chief-wire::file-field-upload` | `chief-wire-assets::file-field-asset-uploader` |
| Assets | `chief-wire::file-field-edit` | `chief-wire-assets::file-field-asset-editor` |
| Assets | `chief-wire::file-field-choose` | `chief-wire-assets::file-field-asset-chooser` |
| Assets | `chief-wire::file-field-choose-external` | `chief-wire-assets::external-file-field-asset-chooser` |
| Assets | `chief-wire::file-upload` | `chief-wire-assets::gallery-asset-uploader` |
| Assets | `chief-wire::file-edit` | `chief-wire-assets::gallery-asset-editor` |
| Assets | `chief-wire::asset-delete` | `chief-wire-assets::asset-delete-dialog` |
| Forms | `chief-form::dialog` | `chief-wire-form::action-dialog` |
| Forms | `chief-wire::form` | `chief-wire-form::model-form` |
| Forms | `chief-wire::edit-form` | `chief-wire-form::edit-model-form` |
| Forms | `chief-wire::repeat` | `chief-wire-form::repeat-field-editor` |
| Fragments | `chief-fragments::contexts` | `chief-wire-fragments::fragment-contexts` |
| Fragments | `chief-wire::edit-context` | `chief-wire-fragments::edit-context` |
| Fragments | `chief-wire::add-context` | `chief-wire-fragments::add-context` |
| Fragments | `chief-fragments::context` | `chief-wire-fragments::fragment-context` |
| Fragments | `chief-fragments::add-fragment` | `chief-wire-fragments::add-fragment` |
| Fragments | `chief-fragments::edit-fragment` | `chief-wire-fragments::edit-fragment` |
| States | `chief-wire::state` | `chief-wire-states::model-state` |
| States | `chief-wire::edit-state` | `chief-wire-states::edit-model-state` |
| Menu | `chief-wire::menus` | `chief-wire-menu::menu-list` |
| Menu | `chief-wire::add-menu` | `chief-wire-menu::add-menu` |
| Menu | `chief-wire::edit-menu` | `chief-wire-menu::edit-menu` |
| Models | `chief-wire::create-model` | `chief-wire-models::create-model` |
| Models | `chief-wire::edit-model` | `chief-wire-models::edit-model` |
| Sites | `chief-wire::site-toggle` | `chief-wire-sites::global-site-toggle` |
| Sites | `chief-wire::model-site-toggle` | `chief-wire-sites::model-site-toggle` |
| Sites | `chief-wire::site-selection` | `chief-wire-sites::model-site-selection` |
| Sites | `chief-wire::edit-site-selection` | `chief-wire-sites::edit-model-site-selection` |
| Table | `chief-wire::table` | `chief-wire-table::data-table` |
| URLs | `chief-wire::links` | `chief-wire-urls::model-links` |
| URLs | `chief-wire::edit-links` | `chief-wire-urls::edit-model-links` |

### HotSpots en ImageCrop

| Plugin | Chief 0.10 | Chief 0.11 |
|---|---|---|
| HotSpots | `chief-wire::hotspots` | `chief-wire-hotspots::hot-spot-editor` |
| ImageCrop | `chief-wire::image-crop` | `chief-wire-image-crop::image-cropper` |

Gebruik de nieuwe aliases ook in `dispatch(...)->to(...)`, tests, dynamisch opgebouwde componentnamen en pluginsecties.

## 5. Belangrijke PHP-classmappings

### Assets: componenten en contracten

| Chief 0.10 | Chief 0.11 |
|---|---|
| `Thinktomorrow\Chief\Assets\Livewire\GalleryComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\AssetGallery` |
| `Thinktomorrow\Chief\Assets\Livewire\FileFieldUploadComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\FileFieldAssetUploader` |
| `Thinktomorrow\Chief\Assets\Livewire\FileFieldEditComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\FileFieldAssetEditor` |
| `Thinktomorrow\Chief\Assets\Livewire\FileFieldChooseComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\FileFieldAssetChooser` |
| `Thinktomorrow\Chief\Assets\App\ExternalFiles\FileChooseExternalComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\ExternalFileFieldAssetChooser` |
| `Thinktomorrow\Chief\Assets\Livewire\FileUploadComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\GalleryAssetUploader` |
| `Thinktomorrow\Chief\Assets\Livewire\FileEditComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\GalleryAssetEditor` |
| `Thinktomorrow\Chief\Assets\Livewire\AssetDeleteComponent` | `Thinktomorrow\Chief\Assets\UI\Livewire\AssetDeleteDialog` |
| `Thinktomorrow\Chief\Assets\Livewire\PreviewFile` | `Thinktomorrow\Chief\Assets\UI\Livewire\PreviewFile` |
| `Thinktomorrow\Chief\Assets\Livewire\HasPreviewFiles` | `Thinktomorrow\Chief\Assets\UI\Livewire\HasPreviewFiles` |
| `Thinktomorrow\Chief\Assets\Livewire\HasSyncedFormInputs` | `Thinktomorrow\Chief\Assets\UI\Livewire\HasSyncedFormInputs` |

### Assets: alle verplaatste traits

Voor elk item hieronder wijzigt de prefix van `Thinktomorrow\Chief\Assets\Livewire\Traits\` naar `Thinktomorrow\Chief\Assets\UI\Livewire\Traits\`:

| Trait |
|---|
| `EmitsToNestables` |
| `FileUploadDefaults` |
| `InteractsWithBasename` |
| `InteractsWithChoosingAssets` |
| `InteractsWithForm` |
| `InteractsWithGallery` |
| `InteractsWithGroupedForms` |
| `RenamesErrorBagFileAttribute` |
| `ShowsAsDialog` |

### Forms, Fragments en Models

| Chief 0.10 | Chief 0.11 |
|---|---|
| `Thinktomorrow\Chief\Forms\Dialogs\Livewire\DialogComponent` | `Thinktomorrow\Chief\Forms\UI\Livewire\ActionDialog` |
| `Thinktomorrow\Chief\Forms\UI\Livewire\FormComponent` | `Thinktomorrow\Chief\Forms\UI\Livewire\ModelForm` |
| `Thinktomorrow\Chief\Forms\UI\Livewire\EditFormComponent` | `Thinktomorrow\Chief\Forms\UI\Livewire\EditModelForm` |
| `Thinktomorrow\Chief\Forms\UI\Livewire\RepeatComponent` | `Thinktomorrow\Chief\Forms\UI\Livewire\RepeatFieldEditor` |
| `Thinktomorrow\Chief\Forms\Dialogs\Livewire\DialogReference` | `Thinktomorrow\Chief\Forms\UI\Livewire\References\DialogReference` |
| `Thinktomorrow\Chief\Forms\Dialogs\Livewire\TableActionDialogReference` | `Thinktomorrow\Chief\Forms\UI\Livewire\References\TableActionDialogReference` |
| `Thinktomorrow\Chief\Fragments\UI\Livewire\Context\AddContext` | `Thinktomorrow\Chief\Fragments\UI\Livewire\AddContext` |
| `Thinktomorrow\Chief\Fragments\UI\Livewire\Context\EditContext` | `Thinktomorrow\Chief\Fragments\UI\Livewire\EditContext` |
| `Thinktomorrow\Chief\Fragments\UI\Livewire\Context\Context` | `Thinktomorrow\Chief\Fragments\UI\Livewire\FragmentContext` |
| `Thinktomorrow\Chief\Fragments\UI\Livewire\Context\Contexts` | `Thinktomorrow\Chief\Fragments\UI\Livewire\FragmentContexts` |
| `Thinktomorrow\Chief\Fragments\UI\Livewire\Fragment\AddFragment` | `Thinktomorrow\Chief\Fragments\UI\Livewire\AddFragment` |
| `Thinktomorrow\Chief\Fragments\UI\Livewire\Fragment\EditFragment` | `Thinktomorrow\Chief\Fragments\UI\Livewire\EditFragment` |
| `Thinktomorrow\Chief\Models\UI\Livewire\CreateModelComponent` | `Thinktomorrow\Chief\Models\UI\Livewire\CreateModel` |
| `Thinktomorrow\Chief\Models\UI\Livewire\EditModelComponent` | `Thinktomorrow\Chief\Models\UI\Livewire\EditModel` |

Let bij `CreateModel` op een importconflict met `Thinktomorrow\Chief\Models\App\Actions\CreateModel`; alias de action bijvoorbeeld als `CreateModelAction`.

### Menu, Table, Sites, States en URLs

| Chief 0.10 | Chief 0.11 |
|---|---|
| `Thinktomorrow\Chief\Menu\UI\Livewire\Menus` | `Thinktomorrow\Chief\Menu\UI\Livewire\MenuList` |
| `Thinktomorrow\Chief\Table\Livewire\TableComponent` | `Thinktomorrow\Chief\Table\UI\Livewire\DataTable` |
| `Thinktomorrow\Chief\Table\Livewire\TreeModels` | `Thinktomorrow\Chief\Table\UI\Livewire\TreeModels` |
| `Thinktomorrow\Chief\Table\Livewire\Concerns\*` | `Thinktomorrow\Chief\Table\UI\Livewire\Concerns\*` |
| `Thinktomorrow\Chief\Sites\UI\Livewire\SiteToggle\GlobalSiteToggle` | `Thinktomorrow\Chief\Sites\UI\Livewire\GlobalSiteToggle` |
| `Thinktomorrow\Chief\Sites\UI\Livewire\SiteToggle\ModelSiteToggle` | `Thinktomorrow\Chief\Sites\UI\Livewire\ModelSiteToggle` |
| `Thinktomorrow\Chief\Sites\UI\Livewire\SiteSelect\SiteSelection` | `Thinktomorrow\Chief\Sites\UI\Livewire\ModelSiteSelection` |
| `Thinktomorrow\Chief\Sites\UI\Livewire\SiteSelect\EditSiteSelection` | `Thinktomorrow\Chief\Sites\UI\Livewire\EditModelSiteSelection` |
| `Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\State` | `Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\ModelState` |
| `Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\EditState` | `Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\EditModelState` |
| `Thinktomorrow\Chief\Urls\UI\Livewire\Links\Links` | `Thinktomorrow\Chief\Urls\UI\Livewire\ModelLinks` |
| `Thinktomorrow\Chief\Urls\UI\Livewire\Links\EditLinks` | `Thinktomorrow\Chief\Urls\UI\Livewire\EditModelLinks` |

`Thinktomorrow\Chief\Table\Table` blijft de definitie/value van een Chief-tabel. Alleen de renderende Livewire-component wordt `Thinktomorrow\Chief\Table\UI\Livewire\DataTable`. Vervang `Table` dus niet door `DataTable` in resource- of tabeldefinities.

### Plugins

| Chief 0.10 | Chief 0.11 |
|---|---|
| `Thinktomorrow\Chief\Plugins\HotSpots\HotSpotComponent` | `Thinktomorrow\Chief\Plugins\HotSpots\UI\Livewire\HotSpotEditor` |
| `Thinktomorrow\Chief\Plugins\ImageCrop\ImageCropComponent` | `Thinktomorrow\Chief\Plugins\ImageCrop\UI\Livewire\ImageCropper` |

De oude experimentele `Thinktomorrow\Chief\Assets\Plugins\ImageCropComponent` is verwijderd en heeft geen compatibele vervanger; gebruik de ImageCrop-plugincomponent hierboven.

## 6. Domeinnamespaces en custom extensies

Chief registreert nu deze Livewire-families met `Livewire::addNamespace()`:

| Aliasfamilie | PHP-namespace | Viewbasis |
|---|---|---|
| `chief-wire-assets` | `Thinktomorrow\Chief\Assets\UI\Livewire` | `src/Assets/UI/views/livewire` |
| `chief-wire-form` | `Thinktomorrow\Chief\Forms\UI\Livewire` | `src/Forms/UI/views/livewire` |
| `chief-wire-fragments` | `Thinktomorrow\Chief\Fragments\UI\Livewire` | `src/Fragments/UI/views/livewire` |
| `chief-wire-menu` | `Thinktomorrow\Chief\Menu\UI\Livewire` | `src/Menu/UI/views/livewire` |
| `chief-wire-models` | `Thinktomorrow\Chief\Models\UI\Livewire` | `src/Models/UI/views/livewire` |
| `chief-wire-sites` | `Thinktomorrow\Chief\Sites\UI\Livewire` | `src/Sites/UI/views/livewire` |
| `chief-wire-states` | `Thinktomorrow\Chief\ManagedModels\States\UI\Livewire` | `src/ManagedModels/States/UI/views/livewire` |
| `chief-wire-table` | `Thinktomorrow\Chief\Table\UI\Livewire` | `src/Table/UI/views/livewire` |
| `chief-wire-urls` | `Thinktomorrow\Chief\Urls\UI\Livewire` | `src/Urls/UI/views/livewire` |
| `chief-wire-hotspots` | `Thinktomorrow\Chief\Plugins\HotSpots\UI\Livewire` | `src/Plugins/HotSpots/UI/views/livewire` |
| `chief-wire-image-crop` | `Thinktomorrow\Chief\Plugins\ImageCrop\UI\Livewire` | `src/Plugins/ImageCrop/UI/views/livewire` |

Gebruik voor eigen Chief-extensies dezelfde structuur: domeincode onder `UI/Livewire`, gewone views onder `UI/views` en Livewire-views onder `UI/views/livewire`. Registreer een projectspecifieke, unieke aliasfamilie in de serviceprovider:

```php
Livewire::addNamespace(
    'project-wire-catalog',
    classNamespace: 'App\\Catalog\\UI\\Livewire',
    classPath: app_path('Catalog/UI/Livewire'),
    classViewPath: app_path('Catalog/UI/views/livewire'),
);
```

Gebruik kebab-case componentnamen en laat PHP-classnaam, bestandsnaam en viewnaam logisch overeenkomen. Registreer niet opnieuw een `chief-wire-*` namespace vanuit het project.

## 7. Livewire 4-controlelijst

Lees naast deze gids altijd de [officiële Livewire 4 upgrade guide](https://livewire.laravel.com/docs/4.x/upgrading). Gebruik voor toekomstige geautomatiseerde audits de projectspecifieke skill op `.agents/skills/chief-upgrade-0-10-to-0-11/SKILL.md`; voer de audit opnieuw uit met de dan actuele skill en geïnstalleerde Chief-broncode.

### Configuratie en routes

- [ ] Vergelijk een gepubliceerde `config/livewire.php` met Livewire 4; overschrijf het projectbestand niet blind.
- [ ] Hernoem `layout` naar `component_layout` en `lazy_placeholder` naar `component_placeholder`.
- [ ] Beoordeel `smart_wire_keys`, `component_locations`, `component_namespaces`, `make_command` en `csp_safe`.
- [ ] Gebruik voor nieuwe full-page componenten `Route::livewire()`; controleer bestaande `Route::get(..., Component::class)` routes.
- [ ] Pas custom `Livewire::setUpdateRoute()` aan naar `function ($handle, $path)` en registreer `Route::post($path, $handle)` zodat de hash behouden blijft.
- [ ] Controleer custom asset URL-, script route- en update endpointconfiguratie.

### Blade en synchronisatie

- [ ] Sluit elke `<livewire:... />`-tag of gebruik een expliciete sluit-tag.
- [ ] Voeg bij gewenst Livewire 3-gedrag `.live` toe vóór `.change` of `.blur`: `wire:model.live.change` en `wire:model.live.blur`.
- [ ] Voeg `.deep` toe waar `wire:model` op een container bewust events van kinderen moet ontvangen.
- [ ] Vervang `wire:scroll` door `wire:navigate:scroll` in persistente navigate-containers.
- [ ] Verwijder modifiers van `wire:transition`; Livewire 4 gebruikt de View Transitions API.
- [ ] Controleer array/object update hooks: volledige vervanging triggert één geconsolideerde hook, losse indexwijzigingen blijven granulair.
- [ ] Controleer bracket notation in `wire:model`; sleutels met letterlijke blokhaken veranderen van betekenis.
- [ ] Test unknown-prop/attribute forwarding naar componenten en slots; Livewire 4 kan onbekende attributen via `$attributes` doorgeven.

### PHP en JavaScript

- [ ] Pas `stream()` aan van `to:` naar `name:` en controleer de nieuwe argumentvolgorde. Gebruik `el:` alleen wanneer bewust naar een elementselector wordt gestreamd.
- [ ] Vervang `Livewire.hook('request', ...)` door `Livewire.interceptRequest(...)`.
- [ ] Vervang complexe `commit` hooks door `interceptMessage(...)`.
- [ ] Registreer custom directives op `livewire:init` en gebruik de aangeboden `cleanup()` om listeners, observers en widgets op te ruimen.
- [ ] Controleer dubbele registratie bij Vite/HMR, Barba of herhaalde scriptinitialisatie.
- [ ] Gebruik named payloads voor PHP-events, bijvoorbeeld `$this->dispatch('searched', term: $this->search)`.
- [ ] Lees browserpayloads via `event.detail`, bijvoorbeeld `event.detail.term`; behandel niet langer een array als eerste positioneel argument.
- [ ] Controleer `Livewire.on(...)`, Alpine `$dispatch(...)`, native `CustomEvent` en componentgerichte `->to(...)` samen.
- [ ] Regressietest alle `@entangle`-koppelingen, vooral `.live`, selectors en conditionele DOM.

### Uploads en endpoints

- [ ] Test temporary uploads, meerdere bestanden, MIME-/groottevalidatie, previews, verwijderen, herordenen en externe assets.
- [ ] Controleer CORS, signed temporary URLs, reverse proxy body limits, PHP uploadlimieten en object-storage endpoints.
- [ ] Sta het strikt begrensde prefix `/livewire-{hash}/...` toe voor de benodigde update-, upload-, preview-, script-, source-map- en componentasset-routes. De hash bestaat uit acht hexadecimale tekens; productie gebruikt normaal `livewire.min.js`.
- [ ] Hardcode nooit de hash; die is afgeleid van `APP_KEY` en wijzigt bij keyrotatie.
- [ ] Controleer firewall, CDN, WAF en Varnish op prefix- of CSRF-uitzonderingen die alleen `/livewire/` herkennen.

## 8. Chief-specifieke wijzigingen

| Chief 0.10 | Chief 0.11 | Actie |
|---|---|---|
| state action `transition(...)` | `applyTransition(...)` | Vervang directe `$wire.transition(...)` calls en tests. |
| event `open-edit-state-{componentId}` | `open-edit-model-state-{componentId}` | Vervang listeners en dispatches; dit is de Livewire-component-ID, niet de model-ID. |
| event `open-edit-links` | `open-edit-model-links` | Vervang listeners, dispatches en assertions. |
| event `open-edit-site-selection` | `open-edit-model-site-selection` | Vervang listeners, dispatches en assertions. |
| algemene targets `chief-wire::*` | domeintargets | Gebruik de aliastabel bij `->to(...)`. |
| Livewire request hook in session handler | request interceptor | Rebase een gekopieerde `livewire-session-handler.js`; gebruik `interceptRequest` en `onError`. |
| oude Assets componenten/views | `Assets\UI\Livewire` en `chief-assets::livewire.*` | Vervang imports, aliases en view-overrides. |

De Chief stateflow kan publicatie nu via `StateTransitionGuard` blokkeren wanneer een visitable model geen link heeft. Test daarom de combinatie van statewijziging, linkdialoog en foutmelding, niet alleen de losse stateknop.

## 9. Gepubliceerde en custom views

Behandel elke view onder `resources/views/vendor/...` als een fork, niet als een automatisch bijgewerkt bestand.

1. Inventariseer alle overrides en noteer uit welke Chief-versie ze afkomstig zijn.
2. Vergelijk elke override met zowel Chief 0.10.31 als de Chief 0.11-doelview.
3. Neem projectspecifieke verschillen opnieuw over op de 0.11-view.
4. Vervang aliases, events, includes, props en Livewire 4-syntax.
5. Test de gerenderde pagina en verwijder de override als hij geen projectwaarde meer heeft.

Overschrijf een custom view nooit met `vendor:publish --force`. Publiceer desnoods naar een tijdelijke map buiten het project en vergelijk handmatig.

### Bekende voorbeelden

- Hanolux heeft een URL-override op het oude pad `resources/views/vendor/chief-urls/links/links.blade.php`. De 0.11-basis hoort onder `resources/views/vendor/chief-urls/livewire/model-links.blade.php`. Verplaats/rebase de projectspecifieke inhoud en vervang `chief-wire::edit-links` door `chief-wire-urls::edit-model-links`.
- Rebase de Redactor-guard in `resources/views/vendor/chief/editors/redactor/editor.blade.php`. Behoud projectspecifieke opties, maar neem de actuele Chief-initialisatie, dialog guard en Livewire-sync over. Initialiseer niet dubbel na morphs of navigatie.
- `chief-form::templates.form-in-sidebar` is verwijderd en heeft geen directe vervanger. Bouw de consumers om naar de actuele Chief page/window/dialog/formcompositie; aliasen naar een willekeurige nieuwe view maskeert structurele verschillen.

## 10. Assets, migraties en deployment

### Chief-assets

Kies per project één strategie en documenteer die.

**Symlink/pathstrategie**

- Geschikt voor lokale ontwikkeling met een path-repository.
- Controleer dat deploy tooling symlinks behoudt en de release niet naar een lokale ontwikkelmachine verwijst.
- Gebruik dit niet als impliciete productieafhankelijkheid.

**Gekopieerde/gepubliceerde strategie**

- Bouw of publiceer de assets uit exact dezelfde Chief-commit als `composer.lock`.
- Verwijder oude gehashte chunks alleen als manifest en bestanden atomair worden uitgerold.
- Vergelijk `public/chief/build/manifest.json` en controleer dat alle genoemde bestanden bestaan.
- Cache immutable gehashte bestanden lang, maar cache het manifest en HTML niet op een manier die releases mengt.

Typische projectcommando's moeten aan het eigen `package.json` en deployscript worden aangepast:

```bash
npm ci
npm run build
php artisan optimize:clear
php artisan migrate:status
php artisan migrate --pretend
```

Gebruik het in het project vastgelegde Chief publish/build-commando als dat bestaat. Raadpleeg eerst `php artisan list` en `php artisan vendor:publish --help`; verzin geen tag en gebruik nooit `--force` over beoordeelde overrides.

### Migraties

- Voer `php artisan migrate:status` uit vóór en na de dependency-update.
- Beoordeel `php artisan migrate --pretend` tegen een representatieve databasestructuur.
- Controleer package-migraties, eigen migraties en volgordeconflicten.
- Laat een bevoegde persoon expliciet toestemming geven vóór `php artisan migrate --force` in productie.
- Maak migrations niet stilzwijgend onderdeel van een asset- of cachecommando.

### Netwerklaag

- Pas WAF/firewall/CDN/Varnish-regels aan voor `/livewire-{hash}/...` zonder de hash te hardcoden.
- Laat POST update/upload endpoints nooit publiek cachen.
- Forward cookies, CSRF-header, content type en request body ongewijzigd.
- Controleer body size en time-outs voor uploads.
- Voer `php artisan optimize:clear` uit nadat code, config en views samen zijn geplaatst en bouw daarna alleen de caches die de deployment normaal gebruikt.

## 11. Verificatie

Pas commando's aan aan `composer.json`, `package.json`, PHPUnit-configuraties en CI van het consumer-project. Onderstaande lijst is een sjabloon, geen claim dat ieder project deze scripts heeft.

```bash
composer validate
composer audit
composer test
vendor/bin/phpunit
vendor/bin/phpstan analyse
vendor/bin/pint --test
npm test
npm run build
php artisan route:list
php artisan migrate:status
php artisan optimize:clear
```

Voer eerst gerichte tests uit voor gewijzigde componenten en plugins, daarna de volledige door CI ontdekte suites. Controleer expliciet of PHPUnit de projectplugin-tests werkelijk ontdekt; `No tests executed` of uitgesloten directories zijn geen geslaagde verificatie.

### Handmatige smoke-matrix Chief admin

| Gebied | Scenario's |
|---|---|
| Authenticatie | login, logout, verlopen sessie, session warning, opnieuw aanmelden |
| Dashboard en navigatie | dashboard, projectnav, menu's, deep links, rechten |
| Modellen | lijst, filter, sortering, paginatie, create, edit, delete, bulkactie |
| Forms | tekst/select, `.change`/`.blur`, locale, repeat, validatiefouten, dialogs |
| Fragments | context wisselen, toevoegen, bewerken, sorteren, nested items |
| States | dialog openen, `applyTransition`, guardfout, publiceren/depubliceren |
| Sites en URLs | sitekeuze, locale toggle, link toevoegen/bewerken, redirect |
| Assets | upload, multi-upload, preview, edit, external, delete, reorder, crop, hotspot |
| Editor | Redactor init, typing-sync, dialog/morph herinitialisatie, links en media |
| Tabellen | DataTable render, filters, selection, acties, tree en items per pagina |

### Handmatige smoke-matrix custom frontend

| Gebied | Scenario's |
|---|---|
| Full-page Livewire | directe URL, refresh, back/forward, validation redirect |
| Custom componenten | mount, props, slots, unknown attributes, nested keys |
| Events | PHP dispatch, Alpine/native listener, named payload, `event.detail` |
| Entangle | initiële waarde, user input, server update, conditional render |
| Navigatie | `wire:navigate`, scrollbehoud, transitions, Barba indien aanwezig |
| Uploads | succesvol, te groot, verkeerd type, timeout, object storage |
| Netwerk | hashed JS/update/upload URLs, 419/401/403/500-afhandeling, retry |
| Caching | anoniem/authenticated, CDN/Varnish bypass, nieuwe release-assets |

## 12. Rollbackgrenzen

Definieer vóór deployment drie afzonderlijke grenzen:

| Grens | Rollback |
|---|---|
| Alleen code/dependencies/assets, geen migraties gestart | Zet vorige release, vorig `composer.lock`, vorige assetmap en manifest atomair terug; clear caches. |
| Voorwaarts compatibele migraties uitgevoerd | Rol code alleen terug als 0.10 aantoonbaar met het nieuwe schema werkt; anders herstel database én uploads uit hetzelfde herstelpunt. |
| Destructieve/data-transformerende migraties of productie-schrijfverkeer op 0.11 | Geen losse code-rollback. Stop writes, volg het goedgekeurde database-rollback-/restoreplan en verifieer referentiële integriteit. |

Rol `APP_KEY` niet terug of vooruit als workaround voor Livewire hashes. Een andere key beïnvloedt encrypted data, cookies en alle endpointhashes. Meng nooit oude HTML/manifests met nieuwe Chief-assets.

Leg het go/no-go-moment vast na migraties en vóór heropening voor gebruikers. Bewaar logs van Composer, migraties, assetbuild en smoke-tests bij de release.

## Appendix A. Hanolux

### Momentopname en inventaris

Projectvoorbeeld: `/Users/bencavens/Code/sites/hanolux`.

| Onderdeel | Auditbevinding op 2 september 2026 |
|---|---|
| PHP | `^8.4` |
| Laravel | constraint `^12.51`, lock `12.68.0` |
| Livewire | constraint `^3.7.10`, lock `3.8.7` |
| Chief | lokale symlink/path-repository, constraint `@dev`, lock `dev-main c0a722f` |
| Trader | lokale symlink, `^0.9.7` |
| Chief override | `resources/views/vendor/chief-urls/links/links.blade.php` |
| Redactor override | `resources/views/vendor/chief/editors/redactor/editor.blade.php` |
| Projectnav | `resources/views/vendor/chief/templates/page/nav/nav-project.blade.php` |

De gegenereerde bestanden in `storage/framework/views` bevatten veel oude Chief-aliases. Die zijn geen broncode, maar tonen dat na de codewijzigingen `php artisan optimize:clear` verplicht is.

### Public/showroom

**Verplicht**

- Audit `resources/views/templates/page/template.blade.php` en alle publieke Livewire-componenten op gesloten tags, eventpayloads en `.change`/`.blur` timing.
- Test de showroomroutes, cataloguspagina's, redirects, formulieren, taal-/sitekeuze en rich data na de Laravel/Livewire-update.
- Controleer de Barba-container in `resources/views/templates/page/template.blade.php`; Livewire-DOM en scripts mogen na een Barba-wissel niet dubbel initialiseren.

**Aanbevolen**

- Gebruik `tests/Feature/CatalogPageTest.php`, `tests/Feature/CatalogPageStateRedirectsTest.php`, `tests/Feature/RedirectNormalizationTest.php`, `tests/Feature/ProductRichDataTest.php` en `tests/Feature/SitemapAndFeedsTest.php` als gerichte regressieset.

### Trader frontend

**Verplicht**

- Regressietest add-to-cart navigatie in `app/Plugins/Trader/Shop/resources/assets/js/alpine/add-to-cart.js`, waar `window.barba.go(...)` wordt gebruikt.
- Rebase de Livewire hook in `app/Plugins/Trader/Shop/resources/views/quotation/_partials/form.blade.php` op de Livewire 4 lifecycle/interceptors.
- Controleer quotation-, cart-, checkout-, payment- en accountflows op named events en `event.detail`.

**Aanbevolen**

- Gebruik de bestaande Tradertests onder `tests/Feature/Trader` en de payment-/quotationtests, maar verifieer eerst de test discovery van de geneste directories.

### Trader admin

**Verplicht**

- `app/Plugins/Trader/Admin/Models/Order/Model/OrderEditAssistant.php:38` retourneert `chief-form::templates.form-in-sidebar`. Deze view is verwijderd en heeft geen directe vervanger; bouw deze assistant om naar de 0.11-form/pagecompositie.
- Regressietest `@entangle` in `app/Plugins/Trader/Admin/resources/views/product/sale-price-field.blade.php` en de drie `.live` bindings in `app/Plugins/Trader/Admin/resources/views/category-set/prefill-taxon-ids-helper.blade.php`.
- Audit de Trader-package zelf; een compatibel rootproject compenseert geen oude Chief-imports in de lokale symlink.

### Chief admin

**Verplicht**

- Vervang in `resources/views/redirects/edit.blade.php` de aliases `chief-wire::state`, `chief-fragments::contexts` en `chief-wire::links`.
- Verplaats en rebase `resources/views/vendor/chief-urls/links/links.blade.php` naar `resources/views/vendor/chief-urls/livewire/model-links.blade.php`; vervang op de oude regel 90 `chief-wire::edit-links` door `chief-wire-urls::edit-model-links`.
- Rebase `resources/views/vendor/chief/editors/redactor/editor.blade.php` en behoud de dialog/morph guard zonder dubbele editors.
- Controleer state, URL, fragment, table, asset en modelbeheer tegen alle mappings uit deze gids.

### JavaScript en Barba

**Verplicht**

- `resources/assets/js/livewire-session-handler.js:65` gebruikt `window.Livewire.hook('request', ({ fail }) => ...)`; vervang dit door `interceptRequest(({ onError }) => onError(...))` en behoud `preventDefault()` waar bedoeld.
- Audit `resources/assets/js/barba.js`, `resources/assets/js/scripts.js`, `resources/assets/js/main.js` en Alpine-modules op herhaalde listeners en cleanup.
- Laat Livewire's hashed endpoints buiten Barba page fetching/caching vallen.

**Aanbevolen**

- Breid `tests/Feature/SessionPingRouteTest.php` en `tests/Feature/LivewireErrorModalTest.php` uit met 401/419/500 en hashed update-endpoints.

### Middleware en sessies

**Verplicht**

- Controleer Chief authenticatiemiddleware, session ping, CSRF en redirectgedrag voor `/livewire-{hash}/update`.
- Controleer dat custom middleware geen vaste `/livewire/` prefix gebruikt en dat Barba geen login-/sessionresponse als pagina injecteert.

### Uploads

**Verplicht**

- Test Chief assetuploads én Trader-/projectuploads: single/multiple, validatie, preview, externe file chooser, edit, delete en object storage.
- Controleer proxy-, PHP- en webserverlimieten en de hashed uploadroute.

### Tests

De audit vond een brede Feature-suite, waaronder `tests/Feature/LivewireErrorModalTest.php`, `tests/Feature/SessionPingRouteTest.php`, Chief-/Trader-admin- en catalogustests. Voer projectscript `composer test:chief:mysql` alleen uit met de beschermde testdatabaseconfiguratie die het script vereist. Voor deze documentatie is niets uitgevoerd.

### Deployment

**Verplicht**

- Vervang vóór productie de Chief `@dev` symlink/pathopzet door de vrijgegeven `^0.11`-constraint of leg een bewust, reproduceerbaar artifact vast.
- Audit `bin/deploy-production.sh` via het Composer-script `deploy:production` op `composer install`, assetbuild/publicatie, migratiegoedkeuring, `optimize:clear` en atomische activatie.
- Rol Chief-, Trader- en projectassets uit exact dezelfde dependencyset uit.

## Appendix B. Mauquoy

### Momentopname en inventaris

Projectvoorbeeld: `/Users/bencavens/Code/sites/mauquoy`.

| Onderdeel | Auditbevinding op 2 september 2026 |
|---|---|
| PHP | `^8.4` |
| Laravel | constraint `^11.45`, lock `11.48.0` |
| Chief | constraint `^0.10.18`, lock `0.10.19` |
| Livewire | transitief, lock `3.7.6` |
| Custom Livewire | geen projectspecifieke Livewire-componenten gevonden |
| Chief assets | gekopieerde bestanden in `public/chief/build` met meerdere oude gehashte chunks |
| Overrides | projectnav en `resources/views/vendor/chief/editors/redactor/editor.blade.php` |

### Verplicht

- Upgrade Laravel 11 naar 12 vóór/tegelijk met Chief 0.11 en los alle root- en Traderconstraints op.
- Omdat de Chief-constraint vanaf `0.10.18` kan installeren, bepaal de lockpatch. Is die lager dan 0.10.30, handel eerst de invitation/package-migratie af.
- Controleer `app/Providers/RouteServiceProvider.php:43`: `routes/front.php` wordt via de klassieke provider geladen. Verifieer route-autoload en middleware onder Laravel 12; neem niet aan dat `bootstrap/app.php` dit automatisch overneemt.
- Rebase de Redactor override; publiceer niet met `--force`.
- Maak `public/chief/build` schoon via een atomisch nieuw artifact, niet door willekeurige oude hashes tijdens een live release te verwijderen.
- Beoordeel `php artisan migrate:status` en `--pretend`; de projectmigratie `database/migrations/2025_07_31_102559_change_tables_for_multisite.php` en nieuwe package-migraties vereisen expliciete deploymentgoedkeuring.

### Tests en discovery

De inventaris toont wel `tests/CreatesApplication.php`, maar geen reguliere `*Test.php`-suite in de gebruikelijke projectmap. Tegelijk staan plugintests onder bijvoorbeeld `app/Plugins/Trader/Tests/Admin/GoogleMerchantFeedTest.php`. Controleer `phpunit.xml` en Composer-scripts: deze tests kunnen buiten standaard discovery vallen. Rapporteer niet dat tests slagen als ze niet worden ontdekt.

### Feeds

- Regressietest `app/Plugins/Trader/Admin/Feed/GoogleMerchant/GoogleFeed.php` en `GenerateGoogleFeed.php`.
- Controleer de binding naar `public_path('feeds')` in `app/Plugins/Trader/Shop/TraderServiceProvider.php:195` op schrijfrechten, atomisch schrijven en deploybehoud.
- Verifieer feedroutes/-URLs en geplande generatie na de route- en frameworkupgrade.

### Aanbevolen

- Voeg gerichte tests toe voor Chief admin login, productbeheer, feedgeneratie, frontend checkout en route-autoload.
- Leg vast of Chief-assets worden gepubliceerd tijdens build of als release-artifact worden meegeleverd.

## Appendix C. Aralea

### Momentopname en inventaris

Projectvoorbeeld: `/Users/bencavens/Code/sites/aralea`.

| Onderdeel | Auditbevinding op 2 september 2026 |
|---|---|
| PHP | `^8.4`, Composer platform `8.4.0` |
| Laravel | constraint `^12.0`, lock `12.69.1` |
| Chief | constraint en lock `0.10.31` |
| Livewire | transitief, lock `3.8.7` |
| Oude Chief-aliasreferenties | 16 relevante referenties in custom adminviews |
| Oude `CreateModelComponent`-referenties | 5 volgens de gerichte audit |
| Custom frontend grids | 2: LocationRental en Leercentrum |
| Overrides | projectnav en Redactor |
| Tests | delen van Chief/plugin-tests zijn uitgesloten of niet standaard ontdekt |

### Verplicht

- Vervang de 16 oude Chief-aliasreferenties. Belangrijke bestanden zijn `app/Plugins/Claim/UI/views/back/contact/edit.blade.php`, `app/Plugins/Claim/UI/views/back/registration/edit.blade.php`, `app/Plugins/LocationRental/resources/views/back/request/edit.blade.php`, `app/Plugins/LocationRental/resources/views/back/request/state.blade.php` en `app/Plugins/Leercentrum/resources/views/back/event/edit.blade.php`.
- Vervang alle 5 imports/referenties van `Thinktomorrow\Chief\Models\UI\Livewire\CreateModelComponent` door `CreateModel`, waaronder `app/Plugins/Claim/Tests/Unit/ContactResourceTest.php` en `app/Plugins/Claim/Tests/Unit/AdminRegistrationTest.php`; herhaal de zoekopdracht omdat aantallen kunnen verschuiven.
- Migreer de twee custom grids afzonderlijk: `app/Plugins/LocationRental/Chief/Sets/Location/LocationGridLivewireComponent.php` en `app/Plugins/Leercentrum/Chief/Sets/Event/EventGridLivewireComponent.php`, inclusief hun views en serviceproviderregistraties.
- Controleer `Livewire::component(...)` in `LocationRentalServiceProvider.php` en `LeercentrumServiceProvider.php`. Eigen aliases mogen blijven, maar component lifecycle, views, gesloten tags, events en tests moeten Livewire 4-compatibel zijn.
- Verwijder elke fallback die een vast `/livewire/update` endpoint gebruikt. `setUpdateRoute` moet de door Livewire aangeleverde `$path` registreren zodat `/livewire-{hash}/update` behouden blijft.
- Regressietest de bestaande entangle-selector in de custom frontend zodra die op de actuele branch is gelokaliseerd; controleer DOM replacement, `.live` en selectorstabiliteit.

### Tests

- Controleer uitsluitingen en suites in `phpunit.xml`; tests onder `app/Plugins/*/Tests` en legacy `tests/Chief` kunnen buiten de standaardrun vallen.
- Neem de custom gridtests, Claim admin CRUD, eventregistratie, LocationRental requests en de 5 CreateModel-consumers expliciet op in CI.
- Gebruik de bestaande `tests/Chief/PagesCrudTest.php` en `tests/Chief/FragmentsCrudTest.php` alleen als ze aantoonbaar ontdekt en bijgewerkt zijn.

### Assets en migraties

- Publiceer een complete Chief 0.11-build; `public/chief` bevat in de audit geen volledige actuele buildinventaris waarop veilig kan worden voortgebouwd.
- Beoordeel `database/migrations/2026_07_01_000000_add_allowed_sites_to_chief_resource_tables.php` samen met package-migraties en voorkom dubbele kolommen.
- Rebase `resources/views/vendor/chief/editors/redactor/editor.blade.php`.

## Appendix D. Minimax

### Momentopname en inventaris

Projectvoorbeeld: `/Users/bencavens/Code/sites/minimax`.

| Onderdeel | Auditbevinding op 2 september 2026 |
|---|---|
| PHP | `^8.4` |
| Laravel | constraint `^12.61`, lock `12.63.0` |
| Chief | branchconstraint en lock `dev-ft/fix-hotspots-plugin de5aee1` |
| Livewire | transitief, lock `3.8.2` |
| Squanto | lock `5.1.0`; Chief 0.11 vereist `^6.0.1` |
| Actieve custom componenten | 4 registraties: Blog grid, FAQ set, City search, Split settings dialog |
| Assets | gekopieerde Chief-build in `public/chief/build` |
| Overrides | projectnav en Redactor |

De vier actieve registraties staan in `app/Plugins/Blog/BlogServiceProvider.php`, `app/Providers/ViewServiceProvider.php` (twee componenten) en `app/Plugins/Split/SplitServiceProvider.php`.

### Verplicht

- Vervang in `app/Plugins/Split/UI/Livewire/ExperimentSettingsDialog.php:7` de import `Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog` door `Thinktomorrow\Chief\Assets\UI\Livewire\Traits\ShowsAsDialog`.
- Regressietest alle vier actieve componenten onder Livewire 4 en sluit hun tags.
- `app/View/Livewire/CitySearchComponent.php:30` dispatcht een positionele array: `$this->dispatch('searched', ['term' => $this->search])`. Maak dit een named payload: `$this->dispatch('searched', term: $this->search)` en lees `event.detail.term` in listeners.
- `resources/views/templates/page/layout.blade.php:52` gebruikt `window.Livewire.hook('request', ...)`. Migreer naar `interceptRequest`, met aparte `onError` en `onFailure` waar nodig.
- `app/Http/Middleware/VerifyCsrfToken.php:17` zondert exact `/livewire/update` uit. Beslis met securityreview of Livewirepagina's uit Varnish moeten, of gebruik een nauw beperkte hashed update-uitzondering; schakel CSRF niet globaal uit.
- Audit de interne Livewire dependency en cachelogica in `app/System/Varnish/Cacheable.php`; cached pagina's met oude CSRF/snapshotdata mogen geen 419-loop veroorzaken.
- Rebase `resources/views/vendor/chief/editors/redactor/editor.blade.php` en voorkom dubbele editorinitialisatie.
- Vervang de tijdelijke Chief branchconstraint door `^0.11` zodra de tag beschikbaar is.

### Dode code

`app/View/Livewire/ExperimentSettingsDialog.php` is niet geregistreerd, heeft geen gevonden view en gebruikt nog `$this->emit()`. Bevestig dat hij onbereikbaar is en verwijder hem; de actieve Split-implementatie staat onder `app/Plugins/Split/UI/Livewire`.

### Tests

- `phpunit.xml` verwijst naar niet-bestaande `tests/Feature` en `tests/Unit`; herstel discovery voor bestanden direct onder `tests/` en `tests/Chief` voordat resultaten als bescherming gelden.
- Repareer daarbij oude Chief-imports in `tests/ChiefTestHelpers.php` en de fragmenttests; een van de tests bevat ook een Hanolux-namespace en undefined `Fragmentable`.
- Voeg/activeer tests voor CitySearch eventpayload, Blog grid, FAQ set, Split dialog, 419/sessionafhandeling en hashed endpoints.
- Controleer PHPUnit discovery voor de bestanden direct onder `tests/` en voor `tests/Chief`; claim geen volledige dekking als suites of directories zijn uitgesloten.

### Assets en migraties

- Bouw/publiceer `public/chief/build` atomisch en controleer het manifest; verwijder oude chunks pas bij activatie van de nieuwe release.
- Beoordeel onder andere `database/migrations/2025_04_03_092656_change_tables_for_multisite.php`, `2025_08_11_135802_add_allowed_sites_to_articles.php` en nieuwe package-migraties op overlap.
- Voer `migrate:status` en `migrate --pretend` uit en vraag expliciete migratiegoedkeuring vóór productie.

## Eindcontrole

- [ ] Exacte startpatch en alle open 0.10-migraties zijn gedocumenteerd.
- [ ] Composer gebruikt PHP `^8.4`, Laravel `^12`, Livewire `^4.4` en de vrijgegeven Chief `^0.11`.
- [ ] `composer audit` meldt geen genegeerde advisories.
- [ ] Alle 32 core- en 2 pluginaliases zijn waar relevant vervangen.
- [ ] Alle oude FQCN-imports, inclusief Assets traits en Table DataTable, zijn vervangen.
- [ ] Custom extensions volgen `UI/Livewire` en `UI/views/livewire`.
- [ ] Livewire 4 config, bindings, tags, events, JS interceptors, uploads en hashed endpoints zijn gecontroleerd.
- [ ] Overrides zijn gerebased en niet overschreven.
- [ ] Migraties en assets hebben een expliciet deployment- en rollbackplan.
- [ ] Projectspecifieke scripts, ontdekte tests en de handmatige smoke-matrix zijn uitgevoerd en vastgelegd vóór productie.
