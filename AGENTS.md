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
