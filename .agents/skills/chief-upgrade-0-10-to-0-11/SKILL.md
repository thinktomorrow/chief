---
name: chief-upgrade-0-10-to-0-11
description: Audits and upgrades Chief consumer applications from 0.10 to 0.11, including the required Laravel 12, PHP 8.4, and Livewire 4 compatibility work; use it whenever a repository is preparing, reviewing, applying, or troubleshooting that upgrade.
---

# Chief 0.10 to 0.11

Use this skill in a Chief consumer repository, not to refactor Chief itself. Resolve the repository root first from version-control metadata or the nearest project manifest; never assume the current working directory is the root.

## Modes

- **Audit mode** is the default. If the user has not explicitly authorized edits, inspect only and produce the pre-change report.
- **Apply mode** requires an explicit request to edit. Apply only reported, in-scope changes and then verify them.
- Dependency changes require separate explicit permission. Do not run `composer require`, `composer update`, install packages, or alter lockfiles merely because source edits were authorized.

## Sources And Instructions

1. Discover and obey repository instructions from the root down to each target file, including `AGENTS.md`, agent instruction files, contributor guidance, and local framework conventions.
2. Resolve this skill directory independently of the current working directory. Prefer the Chief root guide at `../../../UPGRADE-0.10-to-0.11.md` relative to this skill directory when it exists. If the skill was copied elsewhere, locate `UPGRADE-0.10-to-0.11.md` from the resolved repository root or Chief package source. If unavailable, state that and use `references/replacements.md` and `references/verification.md` as the bundled baseline.
3. Treat the installed Chief 0.11 source and its package metadata as authoritative if they are newer than the bundled references. Report any disagreement before editing.

## Safety And Scope

1. Inspect version-control status before work. Record all pre-existing modified, staged, and untracked paths. Never overwrite, revert, reset, clean, or broadly restore user work.
2. Define rollback boundaries as the exact project-owned files this run may change. Preserve the pre-change diff for touched dirty files and stop for a direct conflict.
3. Search and edit project-owned source, configuration, tests, scripts, and intentional published overrides only. Exclude `vendor/`, `node_modules/`, `storage/`, generated caches, coverage output, and compiled/public build assets from source replacement. Inspect dependency package source read-only when needed.
4. Do not edit Chief package-owned files in `vendor/thinktomorrow/chief/`. Do not overwrite customized published views or configuration with package copies.

## Audit

1. Audit exact runtime and declared constraints: PHP runtime and Composer platform PHP, `thinktomorrow/chief`, `laravel/framework`, `livewire/livewire`, `thinktomorrow/squanto`, relevant first-party plugins, Composer lock versions, Node runtime, and frontend package/build scripts. Chief 0.11 requires PHP `^8.4|^8.5`, Laravel `^12.0`, Livewire `^4.4`, and consumer Chief constraint `^0.11`; the canonical package also requires Squanto `^6.0.1`.
2. Run the discovery categories in `references/verification.md`. Inventory aliases, classes/traits, Blade views and overrides, events/methods, Livewire directives/hooks, routes and middleware, cache assumptions, assets, migrations, tests, and project scripts.
3. Distinguish exact replacements in `references/replacements.md` from judgment-required changes. Count occurrences by token and file, excluding generated and third-party paths.
4. Identify customized package view overrides by comparing semantics, not only paths. Preserve their markup, authorization, validation, Alpine behavior, and project-specific slots while adapting names and Livewire behavior.
5. Audit Livewire endpoints through route configuration, proxies, web-server rules, CSP/security middleware, and custom admin-environment middleware. Chief 0.11 includes the `AdminEnvironment` fix that recognizes hashed `livewire-*` endpoints; consumers with their own middleware or copied logic still require an audit.
6. Inspect migration status and available migrations, but never run migrations automatically. Report required operator action and use `--pretend` only when supported and explicitly appropriate.
7. Produce a pre-change report containing mode, detected versions, dirty-worktree baseline, scope/rollback boundary, exact replacement counts, judgment-required findings, dependency plan, migration/assets plan, proposed verification, blockers, and assumptions. In apply mode, present this report before changing files.

## Apply

1. Reconfirm edit scope and dependency permission after the pre-change report.
2. Apply deterministic mappings from `references/replacements.md`: longest old token first, then stable lexical order; exact token/path replacements only; one mapping at a time; inspect the diff and recount the old token after each group.
3. Never globally replace short names such as `State`, `Links`, `Context`, `transition`, `dispatch`, `wire:model`, `livewire`, or `view`. Resolve imports, aliases, strings, and tests in context.
4. Handle events, method signatures, Livewire payloads, request hooks, directives, immediate model updates, and component targeting manually. Preserve public project APIs unless the 0.11 contract explicitly changes them.
5. Move or rename project-owned view overrides only after proving which package view they override. Merge the new package structure into custom content; never replace custom views wholesale.
6. Review endpoint, proxy, cache, and security changes independently. Do not weaken authentication, authorization, CSRF, CSP, admin-prefix, no-index, or session protections to make Livewire requests pass. Clear framework caches only when authorized and relevant; record each cache action.
7. Update dependencies only with explicit permission, using the repository's package-manager conventions. Inspect status immediately after dry runs and actual updates. Do not accept unrelated dependency churn silently.
8. Build or publish assets only when the repository convention requires it. Do not hand-edit hashed bundles or manifests. Keep generated files outside replacement scans and report whether they are tracked.
9. Never run database migrations automatically. A source upgrade, dependency update, or test run does not imply migration permission.

## Verify And Report

Use `references/verification.md`. Discover the repository's own checks before choosing commands; run focused checks first, then the broadest safe relevant suite. Verify stale references, component registration/rendering, endpoint behavior, assets, custom views, authorization, and migration status. Do not claim success for checks that were skipped or unavailable.

The final response must use the standardized **Chief 0.11 Upgrade Report** template from `references/verification.md`, including changed files, dependencies, exact and manual changes, verification results, migrations not run, assets, residual risks, rollback boundary, and operator actions.
