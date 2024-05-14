## TODO multilocale

## Locale setup
- chief_urls as intermediate per locale
- chief_urls has context_id reference
- Page can have multiple contexts. Each locale url can have one active context
- A context can have multiple locales (translations)

- addLocalesToContext
- removeLocalesFromContext
- addContext
- CreateDefaultContext
- removeContext
- reorderContexts

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
