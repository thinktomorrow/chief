---
title: Admins, rollen, rechten en log
description: Collega's uitnodigen, hun toegang beheren, je eigen profiel aanpassen en de log raadplegen.
order: 2
roles: [admin]
---

# Admins, rollen, rechten en log

Iedereen die in Chief werkt, heeft een eigen account. Onder **Instellingen** → **Admins** nodig je collega's uit en beheer je hun toegang. Via de **Log** zie je wie wat gedaan heeft.

## Hoe nodig ik een nieuwe admin uit?

1. Ga naar **Instellingen** → **Admins**.
2. Klik op **Voeg admin toe**.
3. Vul **Voornaam**, **Achternaam** en **E-mailadres** in. Het e-mailadres is ook de login.
4. Kies bij **Rechten** één of meer rollen. Zie [Welke rollen zijn er?](#welke-rollen-zijn-er).
5. Klik op **Uitnodiging versturen**.

Je collega krijgt een mail met de knop **Aanvaard uitnodiging** en kiest daarna zelf een wachtwoord. Tot dan staat de account in het overzicht als **Uitgenodigd**.

> [!NOTE]
> Een uitnodiging is 3 dagen geldig. Daarna moet je een nieuwe sturen.

## Welke statussen heeft een admin?

| Status | Betekenis |
| --- | --- |
| **Uitgenodigd** | De uitnodiging is verstuurd, maar nog niet aanvaard. |
| **Actief** | De admin kan inloggen. |
| **Geblokkeerd** | De admin kan niet meer inloggen, of heeft de uitnodiging geweigerd. |

## Hoe stuur ik een uitnodiging opnieuw?

1. Ga naar **Instellingen** → **Admins** en klik op de naam van je collega.
2. Open het menu met de drie puntjes rechtsboven.
3. Klik op **Stuur nieuwe uitnodiging**.

## Hoe pas ik de gegevens of rechten van een admin aan?

1. Ga naar **Instellingen** → **Admins** en klik op de naam van je collega.
2. Pas de gegevens of de rollen bij **Rechten** aan.
3. Klik op **Bewaar**.

## Hoe blokkeer of verwijder ik een admin?

1. Ga naar **Instellingen** → **Admins** en klik op de naam van je collega.
2. Open het menu met de drie puntjes rechtsboven.
3. Kies de actie. De voornaam van je collega staat telkens voor het woord:

| Actie | Gevolg |
| --- | --- |
| **blokkeren** | De admin kan meteen niet meer inloggen. De account blijft bestaan. |
| **deblokkeren** | Een geblokkeerde admin krijgt opnieuw toegang. |
| **verwijderen** | De account wordt definitief verwijderd. Bevestig met **Verwijder gebruiker**. |

> [!WARNING]
> Een verwijderde account kan je niet terughalen. Twijfel je, blokkeer de admin dan liever. De log en de uitnodigingen die deze persoon verstuurde, blijven wel bewaard.

Je eigen account kan je niet blokkeren of verwijderen.

## Welke rollen zijn er?

Een rol bepaalt wat iemand in Chief mag doen. Standaard zijn er deze rollen:

| Rol | Wat die mag |
| --- | --- |
| admin | Alles beheren: inhoud, **Teksten**, **Instellingen**, de **Log** en andere admins. |
| author | Inhoud en **Teksten** aanpassen en het eigen profiel beheren. Geen instellingen of admins. |
| developer | Volledige toegang, ook tot technische onderdelen. Bedoeld voor je ontwikkelaar. |

Je project kan andere of extra rollen hebben. Accounts met de rol developer kan je als admin niet aanpassen of verwijderen.

## Hoe beheer ik rollen en rechten?

Rollen en hun rechten beheer je onder **Instellingen** → **Rollen**. Dat onderdeel zie je enkel als je rol dat toelaat. Standaard is dat alleen voor je ontwikkelaar.

1. Klik op een rol om ze te openen, of op **Rol toevoegen** voor een nieuwe rol.
2. Vul de **Naam** in.
3. Kies bij **Toestemmingen** wat de rol mag doen.
4. Klik op **Rol opslaan** (of **Bewaar rol** bij een nieuwe rol).

De namen van de toestemmingen zijn technisch. Twijfel je welke je nodig hebt, vraag het aan je ontwikkelaar.

## Hoe pas ik mijn eigen profiel aan?

1. Klik onderaan de navigatie op je voornaam. De pagina **Bewerk jouw profiel** opent.
2. Pas onder **Jouw gegevens** je naam of e-mailadres aan en klik op **Bewaar**.

Via dezelfde pagina log je ook uit met **Uitloggen**.

## Hoe wijzig ik mijn wachtwoord?

1. Klik onderaan de navigatie op je voornaam.
2. Klik onder **Wachtwoord wijzigen** op **Wijzig wachtwoord**.
3. Vul een **Nieuw wachtwoord** in en herhaal het bij **Herhaal wachtwoord**.
4. Klik op **Wachtwoord wijzigen**.

Ben je je wachtwoord vergeten, lees dan [Welkom in Chief](../welkom.md#hoe-stel-ik-een-nieuw-wachtwoord-in-als-ik-het-vergeten-ben).

## Wat toont de log?

Onder **Instellingen** → **Log** zie je de recentste acties in de admin, met wie ze uitvoerde en wanneer. Standaard staan daar onder meer het publiceren, offline halen, archiveren, dupliceren en verwijderen van pagina's in. Gewone aanpassingen aan de inhoud staan er niet in.

De acties staan in het Engels, bijvoorbeeld "Published" of "Archived", gevolgd door het soort item. Klik op de naam van een collega om enkel diens acties te zien. Meer over statussen lees je in [Status: draft, publiceren en archiveren](../paginas/status.md).
