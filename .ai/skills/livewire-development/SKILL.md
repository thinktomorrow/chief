---
name: livewire-development
description: Build and debug Livewire components with clear state, efficient rendering, and robust event handling.
---

# Livewire Development

Match existing component style in this repository and keep component state minimal and explicit.

## Component Guidelines

- Keep public properties intentional and typed where possible.
- Use lifecycle hooks and computed values to avoid duplicated logic.
- Keep event names scoped to avoid cross-component collisions.
- Prevent full list reloads when only one item changes.
- Guard expensive rendering behind explicit toggles or lazy-load flags.

## UX / Performance

- Avoid chatty updates; debounce where appropriate.
- Preserve loaded state across refreshes when expected by UI.
- Use targeted re-renders and stable keys.

## Testing

- Add Livewire tests for state transitions and emitted events.
- Assert both data changes and rendered output.
