---
title: Links, URL's en redirects
description: Het webadres van een pagina per site instellen, online of offline zetten, en oude adressen laten doorverwijzen.
order: 3
---

# Links, URL's en redirects

Een link is het webadres waarop bezoekers een pagina vinden. Elke pagina heeft per site een eigen link. In deze gids lees je hoe je een link aanpast, online of offline zet, en wat er gebeurt met oude adressen.

## Waar vind ik de links van een pagina?

Open de pagina. In de rechterkolom staat het blok **Links**. Daar zie je per site het volledige adres van de pagina en de status van de link. Klik op het adres om de pagina op de website te openen.

| Label bij de link | Wat het betekent |
| --- | --- |
| **online** | De link werkt. Bezoekers vinden de pagina op dit adres. |
| **offline** | De link werkt niet. Bezoekers vinden de pagina niet op dit adres. |
| **Online na publicatie** | De link staat online, maar de pagina zelf staat nog in draft. De link werkt zodra je de pagina publiceert. |

Een link werkt dus alleen als de link online staat én de pagina gepubliceerd is. Hoe je een pagina publiceert, lees je in [Status](status.md#hoe-publiceer-ik-een-pagina).

Staat er **Nog geen links toegevoegd.** of **Voeg een link toe**, dan heeft de pagina voor die site nog geen adres.

## Hoe krijgt een nieuwe pagina haar link?

Wanneer je een pagina aanmaakt, maakt Chief voor elke gekozen site meteen een link aan. Het adres is gebaseerd op de titel: "Over ons" wordt bijvoorbeeld `over-ons`. Die link staat nog offline. Publiceer je de pagina, dan zet Chief haar links mee online.

Pas je later de titel aan, dan verandert de link niet mee. Wil je een ander adres, pas dan de link zelf aan.

## Hoe pas ik een link aan?

1. Open de pagina.
2. Klik in het blok **Links** op het potloodicoon (**Links aanpassen**).
3. Er schuift een paneel open met een blok per site. Bovenaan elk blok zie je het volledige adres zoals het zal worden.
4. Pas bij **Link** het laatste deel van het adres aan. Gebruik kleine letters, cijfers en koppeltekens, zonder spaties.
5. Kies bij **Status** of de link **Online** of **Offline** staat.
6. Klik op **Bewaren**.

<!-- screenshot: het paneel "Links aanpassen" met een blok per site, het veld Link, de keuze Status en de lijst Redirects -->

Chief vraagt een link voor elke site waarop de pagina staat. Laat je het veld **Link** leeg, dan zie je "Gelieve een link in te geven". Is het adres al in gebruik door een andere pagina, dan meldt Chief welke pagina dat is. Kies dan een ander adres.

Staat er bij een site "De pagina staat niet langer gepubliceerd op deze site."? Dan heb je die site weggehaald bij de sites van de pagina, maar bestaat de link nog. Lees [Sites, talen en versies](sites-en-talen.md) voor meer uitleg.

## Wat is een redirect?

Wanneer je het adres van een pagina verandert, onthoudt Chief het oude adres. Bezoekers die het oude adres gebruiken, bijvoorbeeld via een bladwijzer of een zoekmachine, komen automatisch op het nieuwe adres terecht. Zo'n doorverwijzing heet een redirect.

Je ziet de redirects van een pagina in het paneel **Links aanpassen**, onder **Redirects**, per site.

Redirects ontstaan ook:

- wanneer je een pagina archiveert en een andere pagina kiest om naar door te linken (lees [Status](status.md#hoe-archiveer-ik-een-pagina));
- wanneer het adres van een bovenliggende pagina verandert (lees [Hoe werken bovenliggende pagina's?](#hoe-werken-bovenliggende-paginas)).

## Hoe verwijder ik een redirect?

Verwijder een redirect alleen als je zeker bent dat niemand het oude adres nog gebruikt.

1. Open **Links aanpassen** zoals hierboven.
2. Klik onder **Redirects** op het rode vuilbakje naast het oude adres.
3. Vergist? Klik op **Ongedaan maken**.
4. Klik op **Bewaren**.

Wie het oude adres daarna nog bezoekt, krijgt een foutpagina (404) te zien.

## Hoe verwijder ik een link?

1. Open **Links aanpassen**.
2. Klik in het blok van de site op het rode vuilbakje naast de status.
3. Klik op **Bewaren**. Tot dan kan je het nog terugdraaien met **Ongedaan maken**.

Wil je een pagina tijdelijk niet tonen op één site, zet de link dan liever op **Offline**. Zo blijft het adres bewaard.

> [!NOTE]
> De link van de homepagina kan je niet verwijderen. Wil je een andere homepagina, kies die dan bij de instellingen. Zie [Hoe stel ik de homepagina in?](#hoe-stel-ik-de-homepagina-in).

## Hoe werken bovenliggende pagina's?

In sommige projecten kan je een pagina onder een andere pagina plaatsen. Je doet dat in de rechterkolom, in het blok **Paginastructuur**, bij **Bovenliggend item**. Je kan dat ook doen door de pagina te slepen in een overzicht met boomstructuur (lees [Overzichten](overzichten.md#hoe-pas-ik-de-volgorde-aan)).

Een pagina met een bovenliggende pagina krijgt een genest adres. Het adres van de bovenliggende pagina komt ervoor. Staat de pagina "Team" onder "Over ons", dan wordt haar adres bijvoorbeeld `over-ons/team`. In **Links aanpassen** zie je het deel van de bovenliggende pagina al staan. In het veld **Link** vul je alleen het laatste deel in.

Chief houdt die adressen voor je bij:

- Kies je een andere bovenliggende pagina, dan krijgt de pagina een nieuw adres.
- Pas je het adres van de bovenliggende pagina aan, dan veranderen de adressen van alle onderliggende pagina's mee.

In beide gevallen maakt Chief een redirect van het oude naar het nieuwe adres. Je hoeft dus niets te herstellen.

## Hoe stel ik de homepagina in?

De homepagina is de pagina die bezoekers zien op het hoofdadres van je site, zonder iets erachter. Je kiest ze bij de instellingen, niet in het blok **Links**. Daarvoor heb je beheerrechten nodig.

1. Ga naar **Instellingen** → **Algemeen**.
2. Kies bij **Homepagina** de pagina, per site. Je kan alleen kiezen uit pagina's die online staan.
3. Klik op **Wijzigingen opslaan**.

De gekozen pagina krijgt het hoofdadres van de site als link. Was er al een andere homepagina, dan krijgt die normaal haar vorige adres terug. Controleer voor de zekerheid haar links. Meer over de instellingen lees je in [Instellingen en sitemap](../beheer/instellingen.md).
