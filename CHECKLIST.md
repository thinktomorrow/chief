CHECKLIST
---------

## Analyse

Bepaalde gegevens zijn nodig voor een juiste opstart van het project. Met een goede analyse
komen deze elementen tijdig aan bod. Bij een eerste meeting bij de klant worden de project
requirements ontbloot. 

- [ ] Initiële meeting met de klant waarbij projectscope wordt bepaald.
- [ ] Opmaak feature listing en requirements obv [feature template](CHECKLIST_FEATURE_LISTING.md)
- [ ] Opzet sitemap. Dit geeft een indicatie voor de horizontale workload.
- [ ] Bepalen techstack obv feature rapport
- [ ] Evt: wireframing obv feature rapport
- [ ] Intern afstemmen van feature rapport met team.
- [ ] Extern afstemmen van feature rapport met klant.

## Design
TODO...

## Hosting setup
		
Hosting- en accountgegevens zijn steeds vertrouwelijk te behandelen. De gegevens van
hosting, email en database dienen in teamleader onder de projectomschrijving te staan
ter inzage van het team. Zorg voor een moeilijke graad van wachtwoorden!
		
- [ ] Contactgegevens huidige server/IT verantwoordelijke
- [ ] Indien nieuwe site: opzet van productie server met 'site komt eraan' pagina.
- [ ] Opzet van staging server met domeinnaam conventie: <project>.thinktomorrow.be
- [ ] Afschermen staging omgeving met htpasswd.
- [ ] Opzet van een staging en productie database.
- [ ] Hostingsetup: php 7.0, gzip enabled, opcache enabled, ssh keys voor dev team.
- [ ] SMTP gegevens voor mailbox te gebruiken voor uitgaand verkeer vanuit de app.
- [ ] Google analytics UA code	


## Project setup

We werken samen aan een project met git because it is awesome. Github issues voor concrete issues.
De Github wiki wordt gebruikt voor meeting notities en onverwerkte feedback.

- [ ] Opzet github project en verlenen rechten aan team
- [ ] Aanmaken master en develop branch. Development gebeurt volgens git flow principe. Voor eerste release wordt steeds in develop branch gewerkt. Na release wordt er vanuit master gewerkt met feature (tijdelijke) branches.
- [ ] Deployment opzet:	SFTP koppeling met staging en productie server. Indien mogelijk atomic. 
- [ ] Opzet framework (Chief / Laravel / ...)
- [ ] Opzet environments: aparte ENV KEYS, session names.
- [ ] Integratie Bugsnag
- [ ] Frontend build setup: sass en scripts, browsersync (live reload), minify and concat css en js, versioning assets


## Slicing

Slicing zorgt voor de omzetting van het design naar effectieve code. Idealiter komt hierbij
zo weinig mogelijk scripting aan te pas

- [ ] Styleguide:  setup vastleggen met sass variables voor kleuren, fonts, buttons, headings, ... 
- [ ] Images aanmaken / binnenhalen. Afbeeldingen (non-uploaded) slicing + optimizen voor mobile / retina
- [ ] Aandacht voor navigatie: Mobile, responsive, Flexible (beheerbaar)
- [ ] Aandacht homepage above the fold
- [ ] Generieke pagina template en wysiwyg content
- [ ] UX aandacht voor gebruikerservaring: subtiele animaties, copy

## Business logic
Extra aandacht voor admin.

- [ ] Cleane API voor frontend integratie
- [ ] Implementeren van de [code quality checklist](CHECKLIST_QUALITY.md)
- [ ] Aanmaken van Seeders
- [ ] Admin beheerbaarheid van site componenten.
- [ ] Admin login form
- [ ] Login credentials voor klant.
- [ ] Dashboard statistics: GA, FB-pixel,...
- [ ] Main features in admin: media gallery, user roles, cropping tool
- [ ] Preview: Doorgeven aan klant van logingegevens voor staging + voor admin. Staging is nog steeds een testomgeving!
- [ ] Preproductie: Vanaf bepaald moment wordt de data op staging beschouwd als preproductie data en mag niet meer worden bezoedeld.


## COPY
- [ ] vertalingen (Lexicon?)		
- [ ] is alle tekst doorgegeven?		
- [ ] Spellings- en grammatica controle langs onze kant
- [ ] Is alle tekst ingegeven in app translations?	

## TESTING
- [ ] Testing door enkele collega's -> feedback via slack
- [ ] Testing van alle forms, links en talen
- [ ] Testing in browsers en met verschillende (echte) toestellen
- [ ] Test with full dataset and with empty tables
