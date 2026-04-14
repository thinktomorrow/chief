## Chief
This package provides a CMS/admin toolkit for Laravel projects, including resources, menus, fragments, URLs, tables, assets, and UI integrations with Blade, Livewire, and Alpine.
### Features
- Chief conventions: follow existing Chief module patterns and extension points instead of custom ad-hoc structures.
- Upgrade-safe integration: extend behavior in app code and never edit `vendor/thinktomorrow/chief` directly.
- Testing-first workflow: keep changes targeted, validate with focused tests, then run broader suites when needed.
- Frontend integration support: maintain consistency across Blade, Livewire, and Alpine implementations.
- Asset build awareness: if frontend changes are not visible, rebuild assets. Example usage:
@verbatim
    <code-snippet name="Chief frontend build workflow" lang="bash">
        npm run dev
        # or
        npm run build
    </code-snippet>
@endverbatim
### Development Guidelines
- Prefer extension over modification; reuse existing Chief APIs and conventions before introducing abstractions.
- Keep Laravel code typed and explicit (validation, authorization, return types, focused services/actions).
- Prevent N+1 queries in Chief listing/detail views by eager loading relations where appropriate.
- Keep app-specific behavior in the host app, not in package/vendor code.
- Avoid breaking public behavior; prefer additive, backwards-compatible defaults.
- Do not create new top-level folders or add dependencies without explicit approval.
- Do not commit secrets or `.env` values.
### Testing & Verification
- Add or update focused tests for changed behavior.
- Prefer module-local tests for module-local behavior (`src/{Module}/Tests`) and shared coverage in `tests/`.
- Run relevant JS specs under `resources/assets/js/tests` for frontend changes.
- Run lint/format tools relevant to edited files.
@verbatim
    <code-snippet name="Run focused test commands" lang="bash">
        vendor/bin/phpunit tests/Unit/Resource/ModelReferenceTest.php
        npm test -- resources/assets/js/tests/bulkselect.spec.js
    </code-snippet>
@endverbatim
