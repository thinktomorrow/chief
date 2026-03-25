---
name: laravel-best-practices
description: Apply Laravel conventions for validation, authorization, queries, and maintainable service-layer code.
---

# Laravel Best Practices

Use existing project conventions first. Favor explicit typing, clear method names, small focused classes, and framework-native features over custom abstractions.

## Core Rules

- Validate all external input (Form Requests where practical).
- Authorize sensitive actions (policies / gates).
- Prevent N+1 with eager loading and constrained queries.
- Keep controllers thin; move orchestration into actions/services.
- Prefer domain-specific exceptions and fail fast on invalid state.
- Reuse existing package patterns and folder structure.

## Testing

- Add focused tests for behavior changes.
- Prefer deterministic tests and avoid hidden side effects.
- Run targeted tests first, then broader suite if needed.
