## TODO multilocale

## Locale setup

- chief_urls not as intermediate per locale
- chief_urls has context_id reference
- Page can have multiple contexts. Each locale url can have one active context
- A context can have multiple locales (translations)
- sites config + sites code namespace (used to be Locales)

- addLocalesToContext
- removeLocalesFromContext
- addContext
- CreateDefaultContext
- removeContext
- reorderContexts

  // TODO: create new context is locale is added
  // Option to duplicate existing context??? -> with same fragments (fragment with these two locales, not 2 separate
  fragments)
  // TODO: create contexts when page is created (witht hte default locales)

## Testsuite after refactor to Controllers (moving away from Resource)

- action tests
- query tests
- controller tests
- UI tests
- Domain tests

## Refactor to controllers

- Remove assistants
- Check manually and provide test for missed assertions

## Refactor to nested

// fragmentmodel
// context_id of all fragments
// parent_id of nested fragments
// root fragments have parent_id = null

- test for nested fragments tree structure
- test for available view attributes: page, context, fragment, parent fragment, ancestor fragments

- Better nestable structure: context (locale) -> root fragment -> fragment -> fragment
- Get Root Fragments
- Get All Fragments
- total, count, levels, ...
- concepts: Page (Resource with context), Context, Root Fragment, Fragment,
- These concepts should be available when rendering view, and admin (e.g. fields method)
    - Page
    - Context
    - Root Fragment
    - Parent Fragment
    - Fragment
    - ancestor fragments

## Locale logic

proceed with new locale logic

- tests for logic
- fragment can belong to multiple contexts of same owner = not shared
- locale switcher on page
- context selection per locale
- locale selection per root fragment

## Site performance

goal is to retrieve content as fast as possible

- cache compiled context: mimic php artisan view:cache behaviour.
- cache chief urls: each url has corresponding model reference and context id based on locale.
- cache entire response
    - cache header and footer
    - cache context
    - dynamic partials (user-context): csrf, session stuff, ...

## General SP improvement

TODO: use this list to loop over all SP. Each SP should have boot method for frontend essentials
and a bootAdmin for the admin booting. Also a register and registerAdmin method. This allows
To make distinction per provider instead of doing this all here in the main service provider.

## Fixed fragments

- hero, cta footer, ... should be provided as editable fragments which are not deletable or sortable.
- This reduces the need for these type of fields on a page model and allows for content per context.
- Same for seo fields they can be added per context as they in essence belong to the view.
- api in database: extra fragment column that overwrites the order: position: first, second, middle, penultimate, last,
  hidden
- hidden is not rendered in view but available in the view: e.g. modal, seo.

## Terminology

- Context: a group of fragments in a nested structure
- Fragment: a piece of content that can be placed in one or more contexts
- Root Fragment: a fragment that is placed at the root of a nested structure
- Section: a fragment that itself can hold fragments
- Shared Fragment: a fragment that is shared between contexts
- Owner: a page that owns one or more contexts 

