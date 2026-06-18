# AGENTS.md

Guide for agentic coding assistants working in `thinktomorrow/chief`.

## 1) Repository At A Glance

- Framework: Laravel package (PHP) with Livewire + Blade.
- Frontend tooling: Vite, Tailwind, plain JS modules, Alpine directives.
- PHP code: `app/` and `src/`.
- PHP tests: `tests/` and `src/*/Tests`.
- JS tests: `resources/assets/js/tests`.

## 2) Setup And Build Commands

- Install PHP dependencies: `composer install`
- Install JS dependencies: `npm install`
- Build frontend assets: `npm run build`
- Watch frontend build: `npm run dev`
- Vite scripts are in `package.json` and use `vite.config.js`.
- Some PHP tests expect built assets at `public/chief/build`.

## 3) Test Commands

### Run all tests

- PHP full suite: `composer test`
- PHP direct: `vendor/bin/phpunit`
- JS full suite: `npm test`

### Run one PHP test file

- `vendor/bin/phpunit tests/Unit/Resource/ModelReferenceTest.php`
- `vendor/bin/phpunit src/Menu/Tests/App/Queries/MenuTreeTest.php`

### Run one PHP test method

- `vendor/bin/phpunit --filter testMethodName`
- `vendor/bin/phpunit tests/Unit/Resource/ModelReferenceTest.php --filter test_it_can_parse_reference`

### Run one PHPUnit testsuite

- `vendor/bin/phpunit --testsuite unit`
- Also available: `application`, `forms`, `assets`, `models`, `fragments`, `menu`, `table`, `sites`, `urls`, `plugins`

### Run one JS test file

- `npm test -- resources/assets/js/tests/bulkselect.spec.js`

### Run JS tests by test name

- `npm test -- -t "selects all items on the current page"`

### Coverage

- Generate PHP coverage: `composer test-coverage`
- Outputs: `build/html-coverage` and `build/coverage.txt`

## 4) Lint, Format, And Static Analysis

### PHP

- Format/fix style: `vendor/bin/pint`
- Pint config: `pint.json` (`preset: laravel`)
- Static analysis (project config): `vendor/bin/phpstan analyse`
- Static analysis (CI-like): `vendor/bin/phpstan analyse src --level=1`

### JavaScript / Blade / CSS

- Prettier write: `npx prettier --write "resources/assets/**/*.{scss,css,js,ts,tsx,blade.php}"`
- ESLint: `npx eslint resources/assets/js`
- ESLint config: `eslint.config.js`
- Prettier config: `.prettierrc.json`
- `lint-staged.config.cjs` runs Prettier on staged asset files.

## 5) CI Behavior To Mirror Locally

- CI runs PHPUnit testsuites, plugin-path tests, Jest, phpstan (level 1), and Pint.
- Workflow files: `.github/workflows/test.yml`, `.github/workflows/test-plugins.yml`, `.github/workflows/jest.yml`, `.github/workflows/static.yml`, `.github/workflows/codestyle.yml`.

## 6) PHP Code Style Guidelines

### Formatting and file layout

- Follow `.editorconfig`: UTF-8, LF, final newline, 4-space indentation.
- Follow PSR-12 and Laravel style (Pint is source of truth).
- Typical order: `<?php` -> `declare(strict_types=1);` (when used) -> `namespace` -> `use` -> class/enum.
- Keep one primary class/enum/interface/trait per file.

### Strict types and typing

- `declare(strict_types=1);` is widely used in `src/` and tests.
- Keep strict types for new domain/app files unless local pattern differs.
- Add parameter/return types whenever practical.
- Prefer typed properties over mixed state.
- Keep phpstan level-1 compatibility at minimum.

### Imports and namespaces

- Use `use` imports; avoid unnecessary fully-qualified names inline.
- Remove unused imports.
- Keep namespace aligned with PSR-4 in `composer.json`.

### Naming conventions

- Classes/interfaces/traits/enums: `PascalCase`.
- Methods/properties/variables: `camelCase`.
- Constants: `UPPER_SNAKE_CASE`.
- Test classes end with `Test`.
- Exception classes are descriptive and usually live in module `Exceptions/` folders.

### Class design

- Prefer `final` for concrete services/actions/events unless extension is intentional.
- Keep classes focused and single-purpose.

### Error handling

- Fail fast on invalid state/input.
- Throw specific exceptions (`InvalidArgumentException` or domain exceptions).
- Reuse existing module exception types when available.
- Avoid swallowing exceptions silently.

## 7) JavaScript And Blade Style Guidelines

### Formatting

- Follow `.prettierrc.json` (`singleQuote: true`, `semi: true`, `tabWidth: 4`, `printWidth: 120`, `trailingComma: es5`).
- Blade formatting is via `prettier-plugin-blade`.

### Imports and modules

- Keep ESM imports at the top of files.
- Preserve local export style while editing existing modules.

### Naming and patterns

- JS variables/functions: `camelCase`; classes: `PascalCase`.
- Keep Alpine directives/components small and state-driven.
- Guard DOM lookups before use.

### JS error handling

- Use `console.error` only for actionable setup/runtime issues.
- Avoid debug logging in committed code.

## 8) Testing Conventions

- Keep module-specific tests near modules (`src/<Module>/Tests`).
- Keep shared integration/unit tests in `tests/`.
- JS specs should end in `.spec.js` under `resources/assets/js/tests`.
- Prefer deterministic tests; mock external effects when possible.

## 9) Agent Workflow Expectations

- Make targeted, minimal changes; avoid broad refactors unless requested.
- Match existing architecture and naming in touched modules.
- Run focused tests first, then broader suites when needed.
- Run formatter/linter for changed languages before finishing.
- Do not commit secrets or modify `.env` values as part of routine changes.

## 10) Cursor/Copilot Rule Files Check

- Checked `.cursor/rules/`: not present.
- Checked `.cursorrules`: not present.
- Checked `.github/copilot-instructions.md`: not present.
- If these files are added later, treat them as higher-priority instructions and update this file.

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

</laravel-boost-guidelines>
