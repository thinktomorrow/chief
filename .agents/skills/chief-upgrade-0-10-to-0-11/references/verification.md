# Verification

Resolve the consumer repository root before running commands. Use repository-native scripts where available, avoid destructive commands, and never run migrations automatically.

## Discovery Searches

Search project-owned files while excluding `vendor/`, `node_modules/`, `storage/`, caches, coverage, and compiled/public builds. Cover these categories:

- Chief and platform declarations: `thinktomorrow/chief`, `laravel/framework`, `livewire/livewire`, `thinktomorrow/squanto`, `php`, Composer `config.platform`, plugin packages, lockfiles, and CI runtime matrices.
- Livewire aliases: `chief-wire::`, `chief-form::dialog`, `chief-fragments::`, all new `chief-wire-*::` namespaces, `<livewire:`, `@livewire`, `Livewire::component`, `Livewire::test`, `->to(`, `emitDownTo`, and `emitToSibling`.
- PHP symbols: old full namespaces from `replacements.md`, imports ending in moved short class names, subclasses, traits, factories, service providers, test fixtures, serialized class names, and configuration strings.
- Views: `chief-assets::`, `chief-form::`, `chief-fragments::`, `chief-menu::`, `chief-states::`, `chief-sites::`, `chief-table::`, `chief-urls::`, `chief-hotspots::`, `chief-image-crop::`, `resources/views/vendor`, published-view manifests, and custom namespace registration.
- Events and methods: `open-edit-state-`, `open-edit-site-selection`, `open-edit-links`, calls to `transition(` on the old state component, `dispatch(`, `emit`, listener attributes/maps, and Livewire test assertions.
- Livewire frontend behavior: `wire:model.change`, `wire:model.defer`, request hooks, `Livewire.hook('request'`, `interceptRequest`, `Livewire.directive`, `livewire:init`, sortable integrations, Alpine dispatches, teleports, and browser-event payloads.
- Endpoints and security: `AdminEnvironment`, `livewire/update`, `livewire/upload-file`, `livewire-`, admin prefix checks, route caches, trusted proxies, reverse-proxy and web-server location rules, CSP, CSRF exclusions, authentication/session middleware, rate limits, and no-index middleware.
- Assets and migrations: Vite/Mix manifests, package publish commands, tracked generated bundles, Chief migration paths, migration status, deployment scripts, and automatic migration hooks.
- Tests: `phpunit.xml*`, `pest.php`, `tests/`, module-local tests, JS test configuration, browser tests, CI workflows, Composer scripts, package scripts, Makefiles, task runners, and project-specific verification scripts.

Record search expressions, exclusions, match counts, and unresolved matches. A zero-match result is evidence only when its scope and exclusions are stated.

## Version And Dependency Checks

Use applicable commands without mutating dependencies:

```text
php -v
composer --version
composer validate
composer show thinktomorrow/chief
composer show laravel/framework
composer show livewire/livewire
composer show thinktomorrow/squanto
composer outdated --direct
composer why thinktomorrow/chief
composer why-not thinktomorrow/chief ^0.11
composer why-not laravel/framework ^12.0
composer why-not livewire/livewire ^4.4
node --version
npm --version
```

Use `composer show --locked <package>` as well when installed and locked versions may differ. Inspect `composer.json`, `composer.lock`, the active PHP binary, Composer platform overrides, CI images, and deployment runtime together.

For dependency solving, prefer an explicitly scoped `composer update thinktomorrow/chief laravel/framework livewire/livewire thinktomorrow/squanto --with-all-dependencies --dry-run` after dependency-change permission. `composer require --dry-run` may still modify `composer.json`; do not use it as the default dry run. Inspect version-control status immediately before and after every Composer dry run and report any mutation rather than reverting it broadly.

## Test And Script Discovery

Before testing, inspect Composer `scripts`, `package.json` scripts, PHPUnit/Pest configuration, JS runner configuration, CI workflows, browser-test configuration, Makefiles, and repository instructions. Detect project-specific wrappers and required services or built assets. Do not invent a test command when the project defines one.

Run in this order when available and permitted:

1. Syntax/static checks for changed files and focused tests covering touched components.
2. Chief/Livewire registration, rendering, event, form, table, asset upload, state, URL, site, fragment, and plugin tests implicated by findings.
3. Project PHP test suite and static analysis.
4. JS unit tests, linting, formatting checks, and production asset build.
5. Browser/end-to-end smoke tests or the manual matrix below.

Record command, exit status, duration when useful, and concise failure cause. Separate pre-existing failures from upgrade regressions only when evidence supports that distinction.

## Stale-Reference Checks

After edits, repeat every discovery search. At minimum, prove there are no project-owned references to:

- The 32 old core aliases (including `chief-form::dialog`) plus `chief-wire::hotspots` and `chief-wire::image-crop`.
- Old full class and trait namespaces in `replacements.md`.
- Old moved view names used by active code or published overrides.
- Old targeted events and `EditState::transition()` calls.
- Custom Livewire 3 request hooks that still depend on `fail` rather than Livewire 4 request interception.

Do not demand zero results for generic tokens such as `transition`, `State`, `Context`, `Links`, `dispatch`, `wire:model`, or `livewire`; inspect those results semantically.

## Endpoint, Proxy, Cache, And Security Checks

- Inspect the actual Livewire routes and endpoint paths in the upgraded application, including hashed `livewire-*` paths.
- Confirm admin-environment detection permits required Livewire requests without making unrelated frontend requests administrative.
- Chief 0.11 includes the `AdminEnvironment` hashed endpoint fix. Audit any consumer-owned middleware, copied `AdminEnvironment` logic, route predicates, or proxy allowlists independently.
- Verify reverse proxies, load balancers, subdirectory deployments, HTTPS termination, trusted proxies, web-server rewrites, and cache/CDN bypass rules preserve Livewire methods, bodies, cookies, and headers.
- Verify authentication, authorization, CSRF, CSP, session expiry/419 handling, no-index behavior, and rate limiting. Never recommend disabling a protection globally as a fix.
- Check route/config/view cache state. Clear or rebuild caches only with permission and repository conventions; report exactly what was changed.

## Manual Browser Matrix

Test representative authorized and unauthorized states on desktop and mobile where applicable:

| Area | Checks |
| --- | --- |
| Admin/session | Login, logout, expired session/419 recovery, unauthorized access, direct admin URL |
| Models/forms | Create, edit, validation, repeat fields, locale switching, dialog/drawer open-save-close |
| Assets | Gallery load, local/external select, upload, edit metadata, delete, crop, hotspots, upload errors |
| Fragments | Add/edit context, add/edit fragment, shared/nested/repeat fragments, locale changes |
| Table/menu | Render, filter, sort, paginate, bulk select/actions, row dialogs, reorder/tree behavior, menu edit |
| Sites/states/URLs | Site toggles/selections, state dialog and transition, URL drawer, publish guard, frontend link |
| Frontend runtime | Console errors, failed network requests, hashed Livewire endpoints, Alpine behavior, teleports, sortable cleanup |

Capture route/path, role, browser, result, console error, and failed request status. Do not claim browser verification from unit tests alone.

## Assets

- Discover whether the repository commits built assets or builds during deployment.
- Use the declared package-manager and lockfile; do not change dependencies without permission.
- Run the repository's normal development/production build as appropriate.
- Verify manifest entries, asset URLs, CSP nonces/hashes, cache busting, and absence of stale Livewire 3 bundles.
- Never edit hashed JS/CSS or manifests manually. Keep build output out of deterministic source replacements and report generated changes separately.

## Migrations

Inspect migration files and run the repository's migration-status command when it is read-only and configured safely. If SQL review is needed, use the framework's `--pretend` support only after confirming the target environment and command semantics. Never run `migrate`, rollback, reset, refresh, fresh, seed, or destructive database commands automatically. State explicitly in every final report: `Migrations run: no` unless the user separately directed an operator-controlled migration.

## Chief 0.11 Upgrade Report

Use this exact section order in the final response:

```markdown
# Chief 0.11 Upgrade Report

## Outcome
- Mode: audit | apply
- Status: ready | completed | blocked | completed with residual risks
- Repository root: ...
- Chief guide used: ...

## Baseline
- Detected versions/constraints: ...
- Dirty worktree before changes: ...
- Scope and rollback boundary: ...

## Changes
- Files changed: ...
- Dependencies/lockfile: changed | unchanged; permission: ...
- Deterministic replacements: ...
- Judgment-required changes: ...
- Custom views preserved: ...
- Endpoint/cache/security handling: ...
- Assets: ...
- Migrations run: no

## Verification
- Passed: ...
- Failed: ...
- Skipped/unavailable: ...
- Stale-reference search: ...
- Browser matrix: ...

## Residual Risks
- ...

## Operator Actions
- ...
```

In audit mode, rename `## Changes` to `## Proposed Changes` and list no file as changed. Keep facts distinct from recommendations, include exact commands that failed or were skipped, and never suggest broad `git restore`, `git reset`, `git clean`, or automatic migration execution.
