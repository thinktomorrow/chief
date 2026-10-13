<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Reading;

use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditEvent;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditEventModel;

final readonly class HistoryFilters
{
    /** @param array<string, mixed> $values */
    public function __construct(private array $values = []) {}

    public static function forEvent(string $eventId): self
    {
        return new self(['event_id' => $eventId]);
    }

    public static function forModel(string $modelType, string $modelId): self
    {
        return new self(['model_type' => $modelType, 'model_id' => $modelId]);
    }

    public function value(string $name): mixed
    {
        return $this->values[$name] ?? null;
    }

    public function hasValue(string $name): bool
    {
        return isset($this->values[$name]) && $this->values[$name] !== '';
    }

    public function isActive(): bool
    {
        return collect($this->values)->except(['page', 'per_page', 'show_all'])->contains(fn ($value) => is_array($value)
            ? collect($value)->contains(fn ($entry) => $entry !== null && $entry !== '')
            : $value !== null && $value !== '');
    }

    public function matches(AuditEvent $event, string $timezone): bool
    {
        if ($this->value('event_id') !== null && (string) $event->getKey() !== (string) $this->value('event_id')) {
            return false;
        }

        foreach (['type', 'category', 'outcome', 'actor_type'] as $key) {
            if ($key === 'type' && is_array($this->value($key))) {
                if (! in_array($event->type, $this->value($key), true)) {
                    return false;
                }

                continue;
            }

            if ($this->hasValue($key) && (string) $event->$key !== (string) $this->value($key)) {
                return false;
            }
        }

        // Match only projected links; a hidden model must never match a filter.
        if (($this->hasValue('model_type') || $this->hasValue('model_id'))
            && ! $event->models->contains(fn (AuditEventModel $link) => (! $this->hasValue('model_type') || $link->model_type === $this->value('model_type'))
                && (! $this->hasValue('model_id') || $link->model_id === (string) $this->value('model_id')))) {
            return false;
        }

        if ($this->value('actor') !== null && ($event->actor_snapshot['id'] ?? null) !== (string) $this->value('actor')) {
            return false;
        }

        $day = $event->occurred_at->setTimezone($timezone)->toDateString();
        if ($this->value('from') !== null && $day < $this->value('from') || $this->value('to') !== null && $day > $this->value('to')) {
            return false;
        }

        if ($this->value('search') !== null && trim((string) $this->value('search')) !== '') {
            $text = implode(' ', array_filter([$event->summary, $event->actor_snapshot['name'] ?? null, ...$event->models->map(fn (AuditEventModel $link) => $link->model_snapshot['name'])->all()]));

            if (mb_stripos($text, (string) $this->value('search')) === false) {
                return false;
            }
        }

        return true;
    }
}
