# Auditplugin

De auditplugin registreert Chief-acties als historiek en maakt die zichtbaar in Chief. De plugin is optioneel: `AuditServiceProvider` wordt niet automatisch via Composer geladen.

## Activeren in je project

1. Registreer `Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider::class` bij de serviceproviders van je Laravel-applicatie (bijvoorbeeld in `bootstrap/providers.php`).
2. Voer `php artisan migrate` uit voor de audittabellen.
3. Voer `php artisan chief-audit:permissions` uit om `view-audit` en `view-full-audit` aan te maken en aan de gewenste Chief-rollen toe te kennen.

Na activering worden Chief-modelacties zoals aanmaken en bewerken gelogd. Dit vult geen historiek van vóór de activering aan.

## Historiek in de sidebar van een model

Voeg het auditvenster toe aan de `fields($model)` van de Chief-resource waarvoor je historiek wilt tonen:

```php
use Thinktomorrow\Chief\Plugins\Audit\UI\AuditPresets;

public function fields($model): iterable
{
    // Je bestaande velden...

    yield from AuditPresets::modelHistoryWindow($model);
}
```

Het venster verschijnt in de sidebar op de pagina van een **bestaand** model, niet op de aanmaakpagina. De gebruiker heeft `view-audit` nodig. Zonder `view-full-audit` ziet die alleen gebeurtenissen voor bestaande modellen waarvoor die ook het `view`-recht op de Chief-resource heeft. Om de bewerkpagina te openen, zijn uiteraard ook de gebruikelijke rechten voor die pagina nodig. `view-full-audit` alleen is niet voldoende: `view-audit` blijft vereist.

Standaard staan de vijf recentste **primaire** gebeurtenissen in de sidebar. Als er meer historiek is, toont **Toon alle historiek** alle zichtbare gebeurtenissen, inclusief secundaire, op dezelfde pagina. Bij een leeg resultaat verschijnt **Geen historiek.**

Voor eigen gebeurtenissen buiten de standaard Chief-acties kun je `Thinktomorrow\Chief\Plugins\Audit\History::log(...)` gebruiken. Koppel ze aan het model met een `AuditModelDTO` met dezelfde morph class en ID als het model; anders verschijnen ze niet in diens sidebar.
