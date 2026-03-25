---
name: alpinejs-development
description: Implement Alpine.js interactions with scoped events, predictable state, and low-overhead DOM updates.
---

# Alpine.js Development

Use Alpine for lightweight UI state and interactions; keep server-driven state in Livewire/PHP.

## Rules

- Keep `x-data` small and local to the component boundary.
- Scope custom events (include a reference key) to avoid global collisions.
- Guard DOM queries and event payload usage.
- Avoid duplicate state between Alpine and Livewire unless explicitly synchronized.
- Prefer progressive enhancement and graceful fallbacks.

## Performance

- Use lazy behaviors (`x-intersect`, conditional rendering) for expensive sections.
- Avoid unnecessary watchers and repeated DOM traversals.
- Keep transitions intentional and minimal.

## Debugging

- Validate event names, payload structure, and listener scope first.
- Reproduce with minimal component state before broad refactors.
