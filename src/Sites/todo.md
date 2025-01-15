# Multisite todo

## Sites config
- ChiefSites and ChiefSite classes (singleton) used in the app to reference the site configuration

## default locale
- default locale is set in the sites config
- acts as a fallback locale when no locale is set on a model
- when default locale is different than site locale, the site locale inputs are hidden in the admin by default. e.g. <a>add specific be value</a>? 
- refers to the used locale for this site when site locale is not set on a model.

## MultiSiteables
- models are:
  - Page (via their urls): Article, Product, Landingpage, ...
    - nl: example.nl (active)
    - be: example.be (active)
    - be_fr: example.be/fr (inactive)
    - fr: example.fr (inactive)
  - Generic models: testimonials, cities, ...
  - Menu: NL menu, French menu, ...
    - nl: NL menu
    - fr: French menu
    - Main Summer menu (draft)
    - Winter catalog menu (draft)
  - Fragments: nl hero, fr hero, ...
  - Specific field value: nl,fr _add be value_ 

## Context and menu drafts
Page contents and menus can be shared between sites.

There can be more than the active contexts/menus as well. This allows to draft a context or menu for a site.
The page urls have a context_id reference. This context_id is the active context.
Menus have a sites_id reference as well. 

Do you really want an entire menu to be drafted? Or just a few menu items? This is also 
possible. Each menu item can be set for a specific site(s). 

## Menu UI
- index screen: list of all active used menus. Per row: name and active sites. Rows are grouped per type.
- general actions on index: add new menu, reorder menus, view drafts

## Context UI
- tab shows all active contexts.
- actions on the tab row are: add new content, view all drafts.
- actions on a context: remove context, put offline (draft), put online
