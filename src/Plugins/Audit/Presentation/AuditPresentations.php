<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Presentation;

use InvalidArgumentException;
use Thinktomorrow\Chief\Plugins\Audit\Reading\VisibleAuditEvent;

final class AuditPresentations
{
    /** @var array<string, string> */
    private array $categories = [];

    /** @var array<string, class-string<AuditType>> */
    private array $types = [];

    /** @var array<string, array{type: string, presentation: ?class-string<AuditEventPresentation>}> */
    private array $bindings = [];

    /** @var array<string, class-string<AuditFilter>> */
    private array $filters = [];

    public function category(string $key, string $label): void
    {
        $this->categories[$key] = $label;
    }

    public function categoryLabel(string $key): string
    {
        return $this->categories[$key] ?? $key;
    }

    /** @param class-string<AuditType> $class */
    public function type(string $key, string $class): void
    {
        if (! is_subclass_of($class, AuditType::class)) {
            throw new InvalidArgumentException('Audit type must implement AuditType.');
        }

        $this->types[$key] = $class;
    }

    /** Re-registering an event replaces a Chief or project binding without changing stored history.
     * @param  class-string<AuditEventPresentation>|null  $presentation
     */
    public function bind(string $event, string $type, ?string $presentation = null): void
    {
        if ($presentation !== null && ! is_subclass_of($presentation, AuditEventPresentation::class)) {
            throw new InvalidArgumentException('Audit presentation must implement AuditEventPresentation.');
        }

        $this->bindings[$event] = ['type' => $type, 'presentation' => $presentation];
    }

    /** @param class-string<AuditFilter> $class */
    public function filter(string $key, string $class): void
    {
        if (! preg_match('/^[a-z][a-z0-9_]*$/', $key) || ! is_subclass_of($class, AuditFilter::class)) {
            throw new InvalidArgumentException('Invalid audit filter registration.');
        }

        $this->filters[$key] = $class;
    }

    /** @return array<string, AuditFilter> */
    public function filters(): array
    {
        return array_map(fn (string $class): AuditFilter => app($class), $this->filters);
    }

    public function presentation(VisibleAuditEvent $event): AuditType
    {
        $binding = $this->bindings[$event->type] ?? null;
        $type = $binding['type'] ?? $event->type;
        $default = $binding !== null && isset($this->types[$type])
            ? app($this->types[$type])
            : new GenericAuditType($event->type, config('chief.audit.types.'.$event->type, []));

        return isset($binding['presentation'])
            ? app($binding['presentation'])->present($event, $default)
            : $default;
    }
}
